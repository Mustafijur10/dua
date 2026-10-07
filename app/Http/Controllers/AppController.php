<?php
namespace App\Http\Controllers;
use App\Models\{Dua,Category,Note,User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
class AppController extends Controller {
 public function dailyVideos(){
  $key='one-islam-youtube-feed-v1';
  $videos=Cache::get($key);
  if(!$videos){
   try{
    $response=Http::connectTimeout(4)->timeout(8)->withUserAgent('NoorDuaDaily/1.0')->get('https://www.youtube.com/feeds/videos.xml?channel_id=UCTX8ZbNDi_HBoyjTWRw9fAg');
    if($response->successful()){
     $feed=@simplexml_load_string($response->body());
     if($feed){
      $atom='http://www.w3.org/2005/Atom';$yt='http://www.youtube.com/xml/schemas/2015';$items=[];
      foreach($feed->children($atom)->entry as $entry){
       $meta=$entry->children($atom);$data=$entry->children($yt);$id=(string)$data->videoId;$title=trim((string)$meta->title);$media=$meta->children('http://search.yahoo.com/mrss/');$group=$media->group->children('http://search.yahoo.com/mrss/');$description=trim((string)$group->description);$summary=trim(preg_split('/\R/u',$description)[0]??'');
       if(preg_match('/^[A-Za-z0-9_-]{6,20}$/',$id)&&$title!=='')$items[]=['id'=>$id,'title'=>$title,'summary'=>$summary,'url'=>'https://www.youtube.com/watch?v='.$id,'thumbnail'=>'https://i.ytimg.com/vi/'.$id.'/hqdefault.jpg','published'=>(string)$meta->published];
       if(count($items)>=20)break;
      }
      if($items){$videos=$items;Cache::put($key,$videos,now()->addHours(2));}
     }
    }
   }catch(\Throwable $e){report($e);}
  }
  return response()->json(['channel'=>'One Islam Productions','videos'=>$videos?:[],'source'=>$videos?'youtube-feed':'curated-fallback','updated_at'=>now()->toISOString()])->header('Cache-Control','no-store, private');
 }
 public function bootstrap(Request $r){return response()->json(['authenticated'=>(bool)$r->user(),'user'=>$r->user()?->only('name','email'),'categories'=>Category::withCount('duas')->orderBy('name')->get(),'duas'=>Dua::with('category')->latest()->get()]);}
 public function login(Request $r){$d=$r->validate(['email'=>'required|email','password'=>'required']);$u=User::where('email',$d['email'])->first();if(!$u||!Hash::check($d['password'],$u->password))return response()->json(['message'=>'Email or password is incorrect.'],422);$r->session()->regenerate();auth()->login($u);return response()->json(['ok'=>true,'user'=>$u->only('name','email')]);}
 public function logout(Request $r){auth()->logout();$r->session()->invalidate();$r->session()->regenerateToken();return response()->json(['ok'=>true]);}
 public function dashboard(){return response()->json(['duas'=>Dua::count(),'categories'=>Category::count(),'notes'=>Note::count(),'recent'=>Dua::with('category')->latest()->take(4)->get(),'notes_list'=>Note::latest()->take(5)->get()]);}
 public function index(){return Dua::with('category')->latest()->get();}
 public function store(Request $r){$v=$r->validate(['title'=>'required|string|max:180','category_id'=>'required|exists:categories,id','subcategory'=>'nullable|string|max:100','arabic'=>'nullable|string','transliteration'=>'nullable|string','translation'=>'nullable|string','reference'=>'nullable|string|max:180','notes'=>'nullable|string','is_favorite'=>'boolean']);return Dua::create($v)->load('category');}
 public function show(Dua $dua){return $dua->load('category');}
 public function update(Request $r,Dua $dua){$dua->update($r->validate(['title'=>'required|string|max:180','category_id'=>'required|exists:categories,id','subcategory'=>'nullable|string|max:100','arabic'=>'nullable|string','transliteration'=>'nullable|string','translation'=>'nullable|string','reference'=>'nullable|string|max:180','notes'=>'nullable|string','is_favorite'=>'boolean']));return $dua->load('category');}
 public function destroy(Dua $dua){$dua->delete();return response()->noContent();}
 public function categories(){return Category::withCount('duas')->orderBy('name')->get();}
 public function saveCategory(Request $r){return Category::create($r->validate(['name'=>'required|string|max:80','icon'=>'nullable|string|max:40','color'=>'nullable|string|max:30']))->loadCount('duas');}
 public function deleteCategory(Category $category){if($category->duas()->exists())return response()->json(['message'=>'Move or delete this category’s duas first.'],422);$category->delete();return response()->noContent();}
 public function notes(){return Note::orderByDesc('pinned')->latest()->get();}
 public function publicNotes(){return Note::latest()->get();}
 public function storeNote(Request $r){return Note::create($r->validate(['title'=>'required|string|max:160','body'=>'nullable|string','pinned'=>'boolean']));}
 public function updateNote(Request $r,Note $note){$note->update($r->validate(['title'=>'required|string|max:160','body'=>'nullable|string','pinned'=>'boolean']));return $note;}
 public function destroyNote(Note $note){$note->delete();return response()->noContent();}
}
