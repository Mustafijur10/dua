<?php
namespace App\Http\Controllers;

use App\Models\LibraryResource;
use App\Models\Masala;
use App\Models\Story;
use App\Models\StorySchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ContentController extends Controller
{
    public function publicContent()
    {
        $today = Carbon::today();
        $schedule = StorySchedule::with('story')->where('is_active', true)->whereDate('starts_on', '<=', $today)
            ->orderByDesc('priority')->orderByDesc('starts_on')->get()
            ->first(fn($item) => $item->appliesOn($today));
        $storyFields=['id','title','slug','story_type','person_name','era_label','short_story','reason','cover_url','is_published'];
        $stories = Story::select($storyFields)->where('is_published', true)->orderBy('story_type')->orderBy('title')->get();
        $index = max(0, ($today->dayOfYear - 1) % max(1, $stories->count()));
        $fallbackStory = $stories->isNotEmpty() ? $stories->get($index) : null;
        $resources = LibraryResource::select(['id','title','slug','resource_type','creator','summary','url','cover_url','is_featured','published_at'])->where('is_published', true)->orderByDesc('is_featured')->orderByDesc('published_at')->orderByDesc('id')->limit(40)->get();
        $todayStory=$schedule?->story ?: $fallbackStory;
        $todayStory=$todayStory?->only($storyFields);
        $masail=Masala::select(['id','title','slug','category','question','short_answer','updated_at'])->where('is_published', true)->orderByDesc('updated_at')->get();
        return response()->json([
            'stories'=>$stories,
            'today_story'=>$todayStory,
            'today_story_is_scheduled'=>(bool)$schedule,
            'resources'=>$resources,
            'masail'=>$masail,
        ]);
    }

    public function publicStory(string $slug){ return Story::where('slug',$slug)->where('is_published',true)->firstOrFail(); }
    public function publicResource(string $slug){ return LibraryResource::where('slug',$slug)->where('is_published',true)->firstOrFail(); }
    public function publicMasala(string $slug){ return Masala::where('slug',$slug)->where('is_published',true)->firstOrFail(); }

    public function adminStories(){ return Story::with('schedules')->orderByDesc('updated_at')->get(); }
    public function storeStory(Request $request){
        $data=$request->validate($this->storyRules());
        $data['slug']=$this->uniqueSlug(Story::class,$data['slug']?:$data['title']);
        return Story::create($data)->load('schedules');
    }
    public function updateStory(Request $request, Story $story){
        $data=$request->validate($this->storyRules());
        $data['slug']=$this->uniqueSlug(Story::class,$data['slug']?:$data['title'],$story->id);
        $story->update($data); return $story->load('schedules');
    }
    public function destroyStory(Story $story){ $story->delete(); return response()->noContent(); }

    public function adminSchedules(){ return StorySchedule::with('story')->orderBy('starts_on')->orderByDesc('priority')->get(); }
    public function storeSchedule(Request $request){ return StorySchedule::create($request->validate($this->scheduleRules())); }
    public function updateSchedule(Request $request, StorySchedule $schedule){ $schedule->update($request->validate($this->scheduleRules())); return $schedule->load('story'); }
    public function destroySchedule(StorySchedule $schedule){ $schedule->delete(); return response()->noContent(); }

    public function adminResources(){ return LibraryResource::orderByDesc('updated_at')->get(); }
    public function storeResource(Request $request){
        $data=$request->validate($this->resourceRules()); $data['slug']=$this->uniqueSlug(LibraryResource::class,$data['slug']?:$data['title']);
        return LibraryResource::create($data);
    }
    public function updateResource(Request $request, LibraryResource $resource){
        $data=$request->validate($this->resourceRules()); $data['slug']=$this->uniqueSlug(LibraryResource::class,$data['slug']?:$data['title'],$resource->id);
        $resource->update($data); return $resource;
    }
    public function destroyResource(LibraryResource $resource){ $resource->delete(); return response()->noContent(); }

    public function adminMasail(){ return Masala::orderByDesc('updated_at')->get(); }
    public function storeMasala(Request $request){
        $data=$request->validate($this->masalaRules()); $data['slug']=$this->uniqueSlug(Masala::class,$data['slug']?:$data['title']);
        return Masala::create($data);
    }
    public function updateMasala(Request $request, Masala $masala){
        $data=$request->validate($this->masalaRules()); $data['slug']=$this->uniqueSlug(Masala::class,$data['slug']?:$data['title'],$masala->id);
        $masala->update($data); return $masala;
    }
    public function destroyMasala(Masala $masala){ $masala->delete(); return response()->noContent(); }

    private function uniqueSlug(string $model, string $source, ?int $ignoreId=null): string
    {
        $base=Str::slug($source) ?: 'entry'; $candidate=$base; $n=2;
        while ($model::where('slug',$candidate)->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))->exists()) $candidate=$base.'-'.$n++;
        return $candidate;
    }
    private function storyRules(): array
    {
        return ['title'=>'required|string|max:160','slug'=>'nullable|string|max:180','story_type'=>'required|in:prophet,sahabi,tabi,atba_tabiin,women_sahabiyyah,women_of_islam,scholar,other','person_name'=>'nullable|string|max:160','era_label'=>'nullable|string|max:120','short_story'=>'nullable|string|max:3000','life_journey'=>'nullable|string|max:50000','reason'=>'nullable|string|max:6000','full_story'=>'nullable|string|max:200000','lessons'=>'nullable|string|max:20000','sources'=>'nullable|array','sources.*'=>'nullable|url|max:1000','cover_url'=>'nullable|url|max:1000','is_published'=>'required|boolean'];
    }
    private function scheduleRules(): array
    {
        return ['story_id'=>'required|exists:stories,id','starts_on'=>'required|date','ends_on'=>'nullable|date|after_or_equal:starts_on','repeat_rule'=>'required|in:once,daily,weekly,monthly,yearly','priority'=>'required|integer|min:1|max:99','is_active'=>'required|boolean'];
    }
    private function resourceRules(): array
    {
        return ['title'=>'required|string|max:180','slug'=>'nullable|string|max:200','resource_type'=>'required|in:islamic_link,video,paper,journal,book,lecture','creator'=>'nullable|string|max:180','summary'=>'nullable|string|max:10000','url'=>'required|url|max:1500','cover_url'=>'nullable|url|max:1000','is_published'=>'required|boolean','is_featured'=>'required|boolean','published_at'=>'nullable|date'];
    }
    private function masalaRules(): array
    {
        return ['title'=>'required|string|max:180','slug'=>'nullable|string|max:200','category'=>'nullable|string|max:100','question'=>'nullable|string|max:5000','short_answer'=>'nullable|string|max:5000','answer'=>'nullable|string|max:200000','references'=>'nullable|string|max:20000','is_published'=>'required|boolean'];
    }
}
