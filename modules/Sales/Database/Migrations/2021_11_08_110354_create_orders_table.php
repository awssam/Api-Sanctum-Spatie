<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();


                //store
                $table->smallInteger('store_id')->nullable()->default(null);
                $table->string('store_name')->nullable()->default(null);

                //customer
                $table->string('customer_name')->nullable()->default(null);
                $table->integer('customer_id')->unsigned()->nullable()->default(null);
                $table->smallInteger('customer_is_guest')->nullable()->default(null);
                $table->integer('customer_group_id')->nullable()->default(null);
                $table->string('customer_email',128)->nullable()->default(null);
                $table->string('customer_firstname',128)->nullable()->default(null);
                $table->string('customer_lastname',128)->nullable()->default(null);
                $table->string('customer_middlename',128)->nullable()->default(null);
                $table->integer('customer_gender')->nullable()->default(null);


                //shipping
                $table->string('shipping_name')->nullable()->default(null);
                $table->string('shipping_address')->nullable()->default(null);
                $table->string('shipping_information')->nullable()->default(null);
                $table->decimal('shipping_and_handling',20,4)->nullable()->default(null);
                $table->string('pickup_location_code')->nullable()->default(null);


                //order info
                $table->string('protect_code')->nullable()->default(null); // secret code [mapper]
                $table->string('status',32)->nullable()->default(null);
                $table->string('reference',50)->nullable()->default(null);
                $table->string('remote_ip',45)->nullable()->default(null);
                $table->integer('cart_id')->nullable()->default(null);
                $table->string('state',32)->nullable()->default(null);

                //currency
                $table->string('base_currency_code',3)->nullable()->default(null);
                $table->string('order_currency_code')->nullable()->default(null);

                //billing
                $table->string('billing_name')->nullable()->default(null);
                $table->string('billing_address')->nullable()->default(null);
                $table->integer('billing_address_id')->nullable()->default(null);

                //payement and pricing
                $table->string('payment_method')->nullable()->default(null);
                $table->decimal('base_grand_total',20,4)->nullable()->default(null);
                $table->decimal('base_total_paid',20,4)->nullable()->default(null);
                $table->decimal('base_subtotal',20,4)->nullable()->default(null);
                $table->decimal('grand_total',20,4)->nullable()->default(null);
                $table->decimal('total_paid',20,4)->nullable()->default(null);
                $table->decimal('subtotal',20,4)->nullable()->default(null);
                $table->decimal('total_due',20,4)->nullable()->default(null);

                //refund
                $table->decimal('total_refunded',20,4)->nullable()->default(null);

                // rewards
                $table->decimal('reward_earn',10,0)->nullable()->default(null);

                // discount
                $table->decimal('base_discount_amount',20,4)->nullable()->default(null);          

                // items
                $table->decimal('base_total_qty_ordered',12,4)->nullable()->default(null);


                // coupon
                $table->string('coupon_code')->nullable()->default(null);
                $table->string('coupon_rule_name')->nullable()->default(null);




            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
