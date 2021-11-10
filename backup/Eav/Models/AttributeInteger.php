<?php

namespace Modules\Eav\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributeInteger extends ObjectModel
{
    use HasFactory;

    // protected $fillable = [];
    public $timestamps = false;

	/**
     * Get the Model that owns the AttributeInteger.
     */
    public function attributableModel()
    {
        return $this->belongsTo(AttributableModel::class);
    }
    
    protected static function newFactory()
    {
        return \Modules\Eav\Database\factories\AttributeIntegerFactory::new();
    }
}
