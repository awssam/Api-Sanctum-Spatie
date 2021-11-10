<?php

namespace Modules\Sales\Models\Order;

use App\ObjectModel;

class OrderItems extends ObjectModel
{

    protected $fillable = [];
    
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
