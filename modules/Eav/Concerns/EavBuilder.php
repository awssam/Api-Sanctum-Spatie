<?php

namespace Modules\Eav\Concerns;
use Modules\Eav\Supports\EavSupport;
use Illuminate\Database\Eloquent\Model;


trait EavBuilder
{


  public function __call($method, $parameters)
    {   

       
         if($method == 'whereAttribute' || $method == 'where' || $method == 'orWhere'){
            return self::caller($this,$this->getQuery(),$parameters,$method);
         }


        if (in_array($method, ['increment', 'decrement'])) {
            return $this->$method(...$parameters);
        }

        if ($resolver = (static::$relationResolvers[get_class($this)][$method] ?? null)) {
            return $resolver($this);
        }

        return $this->forwardCallTo($this->newQuery(), $method, $parameters);
    }


    public static function caller(Model $instance,$query,$parameters,$method)
    {

            $query = ($query->columns) ? $query : $query->addSelect($instance->getTable().'.*');

            $method = 'where';
             if(EavSupport::getType($parameters) == 'value'){
                if($parameters[0] == $instance->getKeyName()){
                    $parameters[0] = $instance->getTable().'.'.$parameters[0];
                }else{
                    $attributes = self::loadEavAttributes();
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


    public function whereAttribute__($attribute_column, $operator = null, $value = null, $boolean = 'and'){
        $attributes = parent::loadEavAttributes();
        if(is_string($attribute_column))
        foreach ($attributes as $key => $attribute) {
            if($attribute['code_name'] == $attribute_column){
                // dd(self::hasJoin(parent::getQuery(),EavSupport::getTable($attribute['type'])));
                $attribute_column = EavSupport::getTable($attribute['type']).'.'.$attribute['field_name'] ;
            }
        }
        
        if(is_string($attribute_column)){
            return self::where($attribute_column, $operator, $value, $boolean);
        }
        if(is_array($attribute_column)){
            return $this->addArrayOfWhereAttributes($attribute_column);
        }
        return $this;
    }

    // public static function orWhereAttribute($attribute_column, $operator = null, $value = null){
    //     return self::orWhere($attribute_column, $operator, $value);
    // }

    // public function whereAttributeIn($attribute_column, $values, $boolean = 'and', $not = false){
    //     return $this;
    // }

    // public function orWhereAttributeIn($attribute_column, $values){
    //     return $this;
    // }

    // public function whereAttributeNotIn($attribute_column, $values, $boolean = 'and'){
    //     return $this;
    // }

    // public function orWhereAttributeNotIn($attribute_column, $values){
    //     return $this;
    // }

    // public function whereAttributeNull($columns, $boolean = 'and', $not = false){
    //     return $this;
    // }

    // public function orWhereAttributeNull($column){
    //     return $this;
    // }

    // public function whereAttributeNotNull($column, $boolean = 'and'){
    //     return $this;
    // }

    // public function whereAttributeBetween($column, array $values, $boolean = 'and', $not = false){
    //     return $this;
    // }

    // public function orWhereAttributeBetween($column, array $values){
    //     return $this->whereAttributeBetween($column, $values, 'or');
    // }

    // public function whereAttributeNotBetween($column, array $values, $boolean = 'and'){
    //     return $this;
    // }

    // public function orWhereAttributeNotBetween($column, array $values){
    //     return $this;
    // }

    // public function orWhereAttributeNotNull($column){
    //     return $this;
    // }

    // public function orHavingAttribute($column, $operator = null, $value = null){
    //     return $this;
    // }

    // public function havingAttributeBetween($column, array $values, $boolean = 'and', $not = false){
    //     return $this;
    // }




    // public function orderByDesc($column){
        
    // }



    // /**
    //  * Add an array of where clauses to the query.
    //  *
    //  * @param  array  $column
    //  * @param  string  $boolean
    //  * @param  string  $method
    //  * @return $this
    //  */
    // protected function addArrayOfWhereAttributes($column, $boolean, $method = 'where')
    // {
    //     return $this->whereNested(function ($query) use ($column, $method, $boolean) {
    //         foreach ($column as $key => $value) {
    //             if (is_numeric($key) && is_array($value)) {
    //                 $query->{$method}(...array_values($value));
    //             } else {
    //                 $query->$method($key, '=', $value, $boolean);
    //             }
    //         }
    //     }, $boolean);
    // }


    // public static function hasJoin(\Illuminate\Database\Query\Builder $Builder, $table)
    // {   
    //     if(is_array($Builder->joins))
    //     foreach($Builder->joins as $JoinClause)
    //     {
    //         if($JoinClause->table == $table)
    //         {
    //             return true;
    //         }
    //     }
    //     return false;
    // }



}
