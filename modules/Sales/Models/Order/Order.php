<?php

namespace Modules\Sales\Models\Order;

use App\ObjectModel;
use Modules\Sales\Models\Cart\Cart;

class Order extends ObjectModel
{

    protected $fillable = [];
    

    public function items() {
        return $this->hasMany(OrderItems::class);
    }

    public function billingAddresses() {
        return $this->hasMany(OrderBillingAddress::class);
    }

    public function shippingAddresses() {
        return $this->hasMany(OrderBillingAddress::class);
    }

    public function cart()
    {
        return $this->hasOne(Cart::class,'id','cart_id');
    }
}
