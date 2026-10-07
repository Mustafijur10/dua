<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    protected $fillable = ['title','slug','story_type','person_name','era_label','short_story','life_journey','reason','full_story','lessons','sources','cover_url','is_published'];
    protected $casts = ['sources'=>'array','is_published'=>'boolean'];
    public function schedules(){ return $this->hasMany(StorySchedule::class); }
}
