<?php

namespace Modules\Eav\Models;

use App\ObjectModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttributableModel extends ObjectModel
{
    use HasFactory;

    protected $fillable = ['model_name','table_name'];
    
	/**
     * Get the Attributes.
     */
    public function attribute()
    {
        return $this->hasMany(Attribute::class);
    }

	/**
     * Get the AttributeVarchars.
     */
    public function attributeVarchar()
    {
        return $this->hasMany(AttributeVarchar::class);
    }

	/**
     * Get the AttributeDatetimes.
     */
    public function attributeDatetime()
    {
        return $this->hasMany(AttributeDatetime::class);
    }

	/**
     * Get the AttributeDecimals.
     */
    public function attributeDecimal()
    {
        return $this->hasMany(AttributeDecimal::class);
    }

	/**
     * Get the AttributeIntegers.
     */
    public function attributeInteger()
    {
        return $this->hasMany(AttributeInteger::class);
    }

	/**
     * Get the AttributeTexts.
     */
    public function attributeText()
    {
        return $this->hasMany(AttributeText::class);
    }


    protected static function newFactory()
    {
        return \Modules\Eav\Database\factories\ModelNameFactory::new();
    }
}
