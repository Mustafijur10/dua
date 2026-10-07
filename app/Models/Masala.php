<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Masala extends Model
{
    protected $table = 'masail';
    protected $fillable = ['title','slug','category','question','short_answer','answer','references','is_published'];
    protected $casts = ['is_published'=>'boolean'];
}
