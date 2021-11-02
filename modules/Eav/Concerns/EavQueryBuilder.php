<?php

namespace Modules\Eav\Concerns;
use Modules\Eav\Supports\EavSupport;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Modules\Eav\Models\Attribute;
use Illuminate\Support\Str;

class EavQueryBuilder 
{



    public static function whereAttribute__NOTUSED($query,$parameters)
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
        $joins = [];
        $existedJoins = [];
        if(is_array($query->joins))
        foreach ($query->joins as $join) {
            $existedJoins[] = $join->table;
        }
        if(is_array($attributes)){
            $model_attributes = self::loadEavAttributes('App\Models\Product');
            foreach ($attributes as $attribute) {
                if ($model_attributes)
                foreach ($model_attributes as $model_attribute) {

                    if($model_attribute['code_name'] == $attribute){

                        if(array_key_exists($model_attribute['type'], $joins)){
                            $joins[$model_attribute['type']]['addSelect'][] = [
                                'code_name' => $model_attribute['code_name'],
                                'field_name' => $model_attribute['field_name']
                            ];
                        }else{
                            $joins[$model_attribute['type']] = [
                                'attributable_model_id' => $model_attribute['attributable_model_id'],
                                'table' =>EavSupport::getTable($model_attribute['type']),
                                'addSelect' =>[
                                    [
                                        'code_name' => $model_attribute['code_name'],
                                        'field_name' => $model_attribute['field_name']
                                    ]
                                ]
                            ];
                        }
                    }
                }
            }
            $key_nm = $query->from.'.id';
            foreach ($joins as $class => $one_join) {
                if(!in_array($one_join['table'],$existedJoins))
                $query->join($one_join['table'], function($join) use ($one_join,$key_nm){
                    $join->on($key_nm, '=', $join->table.'.entity_id')
                        ->where($join->table.'.attributable_model_id', '=', $one_join['attributable_model_id']);
                });
                foreach ($one_join['addSelect'] as $key => $column) {
                    $query->addSelect(array($one_join['table'].'.'.$column['field_name']. ' as '. $column['code_name']));
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
