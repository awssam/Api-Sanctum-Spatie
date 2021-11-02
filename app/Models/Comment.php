<?php

namespace App\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends ObjectModel
{
    use HasFactory;

     /**
     * Get the product that owns the comment.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
