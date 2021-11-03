<?php

namespace Modules\Eav\Concerns;
use Modules\Eav\Supports\EavSupport;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Modules\Eav\Models\Attribute;



class EavQueryBuilder 
{


    // query is "Illuminate\Database\Eloquent\Builder"
    // query is "Illuminate\Database\Query\Builder" will return an std class

    public static function withAttributes($queryBuilder,$attributes){


        $queryBuilderInstance = get_class($queryBuilder);

        $instance = \App\Models\Product::class; // must be fixed


        if($queryBuilderInstance == QueryBuilder::class)
            $query = $queryBuilder; // change 1
        elseif ($queryBuilderInstance == EloquentBuilder::class) 
            $query = $queryBuilder->getQuery();

        $query = ($query->columns) ? $query : $query->addSelect($query->from.'.*');

        
        $joins = [];
        $existedJoins = [];
        if(is_array($query->joins))
        foreach ($query->joins as $join) {
            $existedJoins[] = $join->table;
        }
        if(is_array($attributes)){
            if($queryBuilderInstance == QueryBuilder::class)
                $model_attributes = self::loadEavAttributes($instance);  // change 2
            elseif ($queryBuilderInstance == EloquentBuilder::class) 
                $model_attributes = self::loadEavAttributes(get_class($queryBuilder->getModel()));

            
            foreach ($attributes as $attribute) {
                if ($model_attributes)
                foreach ($model_attributes as $model_attribute) {

                    if($model_attribute['code_name'] == $attribute || $attributes[0] == 'eav'){

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




        if($queryBuilderInstance == QueryBuilder::class){
            $queryBuilder = new EloquentBuilder($query);
            $queryBuilder->setModel(new $instance); // change 3
            $queryBuilder->setQuery($query); // change 3
        }elseif ($queryBuilderInstance == EloquentBuilder::class) 
            $queryBuilder->setQuery($query);
        

        return $queryBuilder;


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

    public static function manageJoins($value='')
    {
        # code...
    }
}
