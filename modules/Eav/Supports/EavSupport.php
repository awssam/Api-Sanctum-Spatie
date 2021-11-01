<?php

namespace Modules\Eav\Supports;

// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

use Modules\Eav\Models\AttributableModel;
use Modules\Eav\Models\Attribute;
use Modules\Eav\Models\AttributeText;
use Modules\Eav\Models\AttributeVarchar;
use Modules\Eav\Models\AttributeInteger;
use Modules\Eav\Models\AttributeDecimal;
use Modules\Eav\Models\AttributeDatetime;

class EavSupport
{


    /**
     * EavSupport::getModelHandler(type,$class) will fetch the needed model for the type u seted using the type and the class caller attributable.
     * @return AttributeValue[TYPE] Model | mixed  
     */
	public static function getModelHandler($handlerType)
	{
        switch ($handlerType) {
            case 'Modules\Eav\Models\AttributeText':
            case AttributeText::class:
            case 'text':
                return new AttributeText();
                break;
            case 'Modules\Eav\Models\AttributeVarchar':
            case AttributeVarchar::class:
            case 'varchar':
                return new AttributeVarchar();
                break;
            case 'Modules\Eav\Models\AttributeInteger':
            case AttributeInteger::class:
            case 'integer':
                return new AttributeInteger();
                break;
            case 'Modules\Eav\Models\AttributeDecimal':
            case AttributeDecimal::class:
            case 'decimal':
                return new AttributeDecimal();
                break;
            case 'Modules\Eav\Models\AttributeDateTime':
            case AttributeDateTime::class:
            case 'date-time':
            case 'datetime':
                return new AttributeDatetime();
                break;
        }
	}

    public static function getTable($handlerType,$class_name = false)
    {   
        if(self::getModelHandler($handlerType))
            return self::getModelHandler($handlerType)->getTable();
        
    }


    public static function registerModel($class_name)
    {   
        try {
            $attr = AttributableModel::where('model_name',$class_name)->first();
            if($attr) return $attr->id;            
        } catch (Exception $e) { /* nothing to catch*/ }
        return AttributableModel::create(['model_name' =>$class_name])->id;
    }


    public static function filterType($attributes)
    {   if(!is_array($attributes)) return ucfirst(gettype($attributes));
        if(count($attributes)) return (gettype($attributes[0]) == 'string') ? "Array" : 'Nested';
    }

    
    public static function getLastUsedColumn($handlerType)
    {
        try {
            $data = Attribute::where([['type','=',get_class(self::getModelHandler($handlerType))]])->orderBy('id', 'desc')->firstOrFail();
            return $data->field_name;
        } catch (\Exception $e) { /* no need to catch anything */}
        return false;
    }

    public static function addColumn($handlerType)
    {
        $col = self::getLastUsedColumn($handlerType);
        if($col === false) { 
            $col = 'v_0';
        }else{
            $col = explode('_',$col);
            $col = 'v_'.(intval($col[1]) + 1);
        }
        switch ($handlerType) {
            case 'integer': self::_addInteger($col,$handlerType);break;
            case 'varchar': self::_addVarchar($col,$handlerType);break;
            case 'decimal': self::_addDecimal($col,$handlerType);break;
            case 'datetime': self::_addDatetime($col,$handlerType);break;
            case 'text': self::_addText($col,$handlerType);break;
        }
        return $col;
    }

    protected static function _addInteger($column_name,$handlerType){Schema::table(self::getTable($handlerType), function($table) use ($column_name) { $table->integer($column_name);});}
    protected static function _addVarchar($column_name,$handlerType){Schema::table(self::getTable($handlerType), function($table) use ($column_name) { $table->string($column_name);});}
    protected static function _addDecimal($column_name,$handlerType){Schema::table(self::getTable($handlerType), function($table) use ($column_name) { $table->decimal($column_name,10,4);});}
    protected static function _addDatetime($column_name,$handlerType){Schema::table(self::getTable($handlerType), function($table) use ($column_name) { $table->dateTime($column_name);});}
    protected static function _addText($column_name,$handlerType){Schema::table(self::getTable($handlerType), function($table) use ($column_name) { $table->text($column_name);});}

    public static function getType($parameters)
    {
        if(is_array($parameters)){
            if(is_array($parameters[0])){
                if(is_array($parameters[0][0])){
                    return 'nested';
                }return 'array';
            }return 'value';
        }
        return false;
    }


    public static function caller(Model $instance,$query,$parameters)
    {

        $query = ($query->columns) ? $query : $query->addSelect($instance->getTable().'.*');
        $method = 'where';
         if(EavSupport::getType($parameters) == 'value'){
            if($parameters[0] == $instance->getKeyName()){
                $parameters[0] = $instance->getTable().'.'.$parameters[0];
            }else{
                $attributes = self::loadEavAttributes($instance);
                foreach ($attributes as $key => $attribute) {
                    if($attribute['code_name'] == $parameters[0]){
                        $parameters[0] = EavSupport::getTable($attribute['type']).'.'.$attribute['field_name'];
                        $tb = $instance->getTable();
                        $key_nm = $instance->getKeyName();
                        $query->join(EavSupport::getTable($attribute['type']), function($join) use ($attribute,$tb,$key_nm){
                            $join->on($tb.'.'.$key_nm, '=', $join->table.'.entity_id')
                            ->on($tb.'.'.$key_nm, '=', $join->table.'.entity_id');
                            }
                        );
                    }
                }
                return $instance->forwardCallTo($query, $method, $parameters);
            }
         } 
    }

    /**
     * loadEavAttributes will fetch all attributes objects of the current attributable class.
     * @return Array Attribute  
     */
    protected static function loadEavAttributes($class)
    {   
        $model_nam = self::registerModel($class);
        try {
            return Attribute::query()->select('field_name','code_name','type','attributable_model_id')->where('attributable_model_id',$model_nam)->get()->toArray();
        } catch (Exception $e) { /* looks empty no fields exists */ }
        return [];
    }


}
