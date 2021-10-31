<?php

namespace Modules\Eav\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Modules\Eav\Concerns\EavBuilder;
use Modules\Eav\Supports\EavSupport;
use Modules\Eav\Events\ModelWasSavedEvent;

use Modules\Eav\Models\Attribute;
use Illuminate\Database\Eloquent\Collection;

trait Attributable
{

    use EavBuilder;

    public $trashedEav = [];

    private $trashedJoins = [];

    private $modelAttribute;

    public function __construct(array $attributes = array())
    {   
        $this->trashedEav = $attributes;
        parent::__construct($attributes);
    }


    public static function bootAttributable()
    {
        static::saving(ModelWasSavedEvent::class.'@saving');
        static::updating(ModelWasSavedEvent::class.'@updating');
        static::saved(ModelWasSavedEvent::class.'@saved');
    }

    /**
     * createAttribute will create a new attribut to this class if not existed before
     * @return Instance of Attribute | Boolean 
     */
    public static function createAttribute($attribute_name,$attribute_type){
        // $key = 'attributes-'.Str::slug(str_replace(['\\','/'], ' ', self::class));
        $reflectionClass = explode('\\', self::class);
        $slug = Str::slug($attribute_name.' '.end($reflectionClass),'-');
        $attribute = Attribute::where('slug','=',$slug)->first();
        $type = get_class(EavSupport::getModelHandler($attribute_type,self::class));

        $field_name = EavSupport::addColumn($attribute_type);
        $model_nam = EavSupport::registerModel(self::class);
        if(!$attribute){
            // Cache::forget($key);
            return Attribute::create([
                'attributable_model_id' => $model_nam,
                'slug' => $slug,
                'field_name' => $field_name,
                'code_name' => $attribute_name,
                'display_name' => $attribute_name,
                'type' => $type
            ]);
        }
        return $attribute;

    }


    /**
     * loadEavAttributes will fetch all attributes objects of the current attributable class.
     * @return Array Attribute  
     */
    protected static function loadEavAttributes()
    {   
        $model_nam = EavSupport::registerModel(self::class);
        try {
            return Attribute::query()->select('field_name','code_name','type')->where('attributable_model_id',$model_nam)->get()->toArray();
        } catch (Exception $e) { /* looks empty no fields exists */ }
        return [];
    }


    // test
    public function organizeSaving($attributes)
    {
        $array = [];
        foreach ($attributes as $trash) {
            $array[$trash->type][$trash->field_name] = $trash->value;
            $array[$trash->type]['entity_id'] = $this->id;
            $array[$trash->type]['model_name'] = get_class($this);
        }
        return $array;
    }

    public function scopeWithAttributes($query,$attributes = false){
        $query = ($query->getQuery()->columns) ? $query : $query->addSelect($this->getTable().'.*');
        foreach (self::loadEavAttributes() as $attribute) {
            if($attributes == false){
                $query = $this->__addAttributeToQuery($query,$attribute);
            }else{            
                foreach ($attributes as $scoop_attribute) {
                    if($attribute['code_name'] == $scoop_attribute)
                        $query = $this->__addAttributeToQuery($query,$attribute);
                }
            }
        }
        return $query;
    }

    /**
     * __addAttributeToQuery will add the attribute to the query
     * @return query
     */
    public function __addAttributeToQuery($query,$attribute)
    {
        $tb = $this->getTable();
        $key_nm = $this->getKeyName();
        if(in_array(EavSupport::getTable($attribute['type']), $this->trashedJoins)){
            return $query->addSelect(array(EavSupport::getTable($attribute['type']).'.'.$attribute['field_name'].' as '.$attribute['code_name']));
        }else{
            $this->trashedJoins[] = EavSupport::getTable($attribute['type']);
            return 
            $query
            ->leftJoin(EavSupport::getTable($attribute['type']), function($join) use ($attribute,$tb,$key_nm){
                    $join->on($tb.'.'.$key_nm, '=', EavSupport::getTable($attribute['type']).'.entity_id');
                    }
                )
            ->addSelect(array(EavSupport::getTable($attribute['type']).'.'.$attribute['field_name'].' as '.$attribute['code_name']));
        }
    }

}


