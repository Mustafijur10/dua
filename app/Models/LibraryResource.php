<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LibraryResource extends Model
{
    protected $table = 'resources';
    protected $fillable = ['title','slug','resource_type','creator','summary','url','cover_url','is_published','is_featured','published_at'];
    protected $casts = ['is_published'=>'boolean','is_featured'=>'boolean','published_at'=>'date'];
}
