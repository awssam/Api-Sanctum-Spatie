<?php

namespace Modules\Eav\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Attribute extends ObjectModel
{
    use HasFactory;

    protected $fillable = ['attributable_model_id','display_name','slug','field_name','code_name','type'];

	/**
     * Get the Model that owns the Attribute.
     */
    public function attributableModel()
    {
        return $this->belongsTo(AttributableModel::class);
    }

    protected static function newFactory()
    {
        return \Modules\Eav\Database\factories\AttributeFactory::new();
    }
}
