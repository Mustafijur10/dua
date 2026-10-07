<?php
namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorySchedule extends Model
{
    protected $fillable = ['story_id','starts_on','ends_on','repeat_rule','priority','is_active'];
    protected $casts = ['starts_on'=>'date','ends_on'=>'date','is_active'=>'boolean'];
    public function story(){ return $this->belongsTo(Story::class); }

    public function appliesOn(Carbon $date): bool
    {
        if (!$this->is_active || !$this->story?->is_published) return false;
        $day = $date->copy()->startOfDay();
        $start = Carbon::parse($this->starts_on)->startOfDay();
        if ($day->lt($start) || ($this->ends_on && $day->gt(Carbon::parse($this->ends_on)->endOfDay()))) return false;
        return match ($this->repeat_rule) {
            'daily' => true,
            'weekly' => $start->diffInDays($day) % 7 === 0,
            'monthly' => $day->day === $start->day,
            'yearly' => $day->format('m-d') === $start->format('m-d'),
            default => $day->isSameDay($start),
        };
    }
}
