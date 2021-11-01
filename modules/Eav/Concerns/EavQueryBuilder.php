<?php

namespace Modules\Eav\Concerns;
use Modules\Eav\Supports\EavSupport;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Modules\Eav\Models\Attribute;


class EavQueryBuilder extends EloquentBuilder
{



    public static function whereAttribute($query,$parameters)
    {
            $query = ($query->columns) ? $query : $query->addSelect($query->from.'.*');
            $tableKey = 'id'; // can be enhanced 

             if(EavSupport::getType($parameters) == 'value'){
                if($parameters[0] == $tableKey){
                    $parameters[0] = $query->from.'.'.$parameters[0];
                }else{
                    $attributes = self::loadEavAttributes('App\Models\Product');

                    foreach ($attributes as $key => $attribute) {
                        if($attribute['code_name'] == $parameters[0]){
                            $parameters[0] = EavSupport::getTable($attribute['type']).'.'.$attribute['field_name'];
                            $tb = $query->from;
                             $query->join(EavSupport::getTable($attribute['type']), function($join) use ($attribute,$tb,$tableKey,$parameters){
                                $join->on($tb.'.'.$tableKey, '=', $join->table.'.entity_id')
                                ->where($join->table.'.attributable_model_id', '=', $attribute['attributable_model_id'])
                                ->where($parameters[0],$parameters[1],$parameters[2]);
                                }
                            );
                        }
                    }
                    // $joins = [];
                    // unset($query->bindings->join[1]);
                    // unset($query->joins[1]);
                    foreach ($query->joins as $key => $join) {
                        // echo $key.":".$join->toSql()."\r\n";
                    //     if(in_array($join->table,$joins)){
                    //         unset($query->joins[$key]);
                    //     }
                    //     $joins[] = $join->table;
                    //     // print_r($join->table);
                    }
                    // dd(count($query->joins));
                    return $query;
                }
             } 
             // }

    }
  


    public static function withAttributes($query,$attributes){
        // // if($attributes == false){
        //     echo "attributes";
        //     dd($attributes,get_class($query));
        //     echo "args";
        //     // dd($args);
        // // }
        dd(count($query->joins));
        $query = ($query->getQuery()->columns) ? $query : $query->addSelect($this->getTable().'.*');
        foreach (self::loadEavAttributes() as $attribute) {
            if($attributes == false){
                $query = $this->__addAttributeToQuery($query,$attribute);
            }else{       
                if(is_array($attributes)){
                    foreach ($attributes as $scoop_attribute) {
                        if($attribute['code_name'] == $scoop_attribute)
                            $query = $this->__addAttributeToQuery($query,$attribute);
                    }

                }else{
                        if($attribute['code_name'] == $attributes)

                            $query = $this->__addAttributeToQuery($query,$attribute);

                }  
            }
        }
        return $query;
    }

    /**
     * loadEavAttributes will fetch all attributes objects of the current attributable class.
     * @return Array Attribute  
     */
    protected static function loadEavAttributes($class)
    {   
        $model_nam = EavSupport::registerModel($class);
        try {
            return Attribute::query()->select('field_name','code_name','type','attributable_model_id')->where('attributable_model_id',$model_nam)->get()->toArray();
        } catch (Exception $e) { /* looks empty no fields exists */ }
        return [];
    }


}
