<?php

namespace Modules\Sales\Models\Order;

use App\ObjectModel;

class OrderShippingAddress extends ObjectModel
{

    protected $fillable = [];
    
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
