<?php

namespace App\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends ObjectModel
{	
    protected $fillable = ['name'];

	use \Modules\Eav\Traits\Attributable;
    use HasFactory;
}
