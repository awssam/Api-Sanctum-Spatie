<?php

namespace Modules\Store\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends ObjectModel
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\Store\Database\factories\StoreFactory::new();
    }
}
