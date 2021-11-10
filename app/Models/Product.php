<?php

namespace App\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends ObjectModel
{	
    protected $fillable = ['name'];

	use \Larattributes\Traits\Attributable;
    use HasFactory;
 	
 	/**
     * Get the comments for the blog post.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
