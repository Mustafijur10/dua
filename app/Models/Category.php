<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Category extends Model { protected $fillable=['name','icon','color']; public function duas(){return $this->hasMany(Dua::class);} }
