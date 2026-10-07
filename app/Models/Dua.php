<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Dua extends Model { protected $fillable=['title','category_id','subcategory','arabic','transliteration','translation','reference','notes','is_favorite']; protected $casts=['is_favorite'=>'boolean']; public function category(){return $this->belongsTo(Category::class);} }
