<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrderTable extends Migration
{
    public function up()
    {
        Schema::create('sales_order', function (Blueprint $table) {




                //store
                $table->smallInteger('store_id',5)->nullable()->default('NULL');
                $table->string('store_name')->nullable()->default('NULL');

                //customer
                $table->string('customer_name')->nullable()->default('NULL');
                $table->integer('customer_id',10)->unsigned()->nullable()->default('NULL');
                $table->smallInteger('customer_is_guest',5)->nullable()->default('NULL');
                $table->integer('customer_group_id',11)->nullable()->default('NULL');
                $table->string('customer_email',128)->nullable()->default('NULL');
                $table->string('customer_firstname',128)->nullable()->default('NULL');
                $table->string('customer_lastname',128)->nullable()->default('NULL');
                $table->string('customer_middlename',128)->nullable()->default('NULL');
                $table->integer('customer_gender',11)->nullable()->default('NULL');


                //shipping
                $table->string('shipping_name')->nullable()->default('NULL');
                $table->string('shipping_address')->nullable()->default('NULL');
                $table->string('shipping_information')->nullable()->default('NULL');
                $table->decimal('shipping_and_handling',20,4)->nullable()->default('NULL');
                $table->string('pickup_location_code')->nullable()->default('NULL');


                //order info
                $table->integer('entity_id',10)->unsigned();
                $table->string('protect_code')->nullable()->default('NULL'); // secret code [mapper]
                $table->string('status',32)->nullable()->default('NULL');
                $table->string('increment_id',50)->nullable()->default('NULL');
                $table->timestamp('created_at')->nullable()->default('NULL');
                $table->timestamp('updated_at')->nullable()->default('NULL');
                $table->string('remote_ip',45)->nullable()->default('NULL');
                $table->integer('quote_id',11)->nullable()->default('NULL');
                $table->string('state',32)->nullable()->default('NULL');

                //currency
                $table->string('base_currency_code',3)->nullable()->default('NULL');
                $table->string('order_currency_code')->nullable()->default('NULL');

                //billing
                $table->string('billing_name')->nullable()->default('NULL');
                $table->string('billing_address')->nullable()->default('NULL');
                $table->integer('billing_address_id',11)->nullable()->default('NULL');

                //payement and pricing
                $table->string('payment_method')->nullable()->default('NULL');
                $table->decimal('base_grand_total',20,4)->nullable()->default('NULL');
                $table->decimal('base_total_paid',20,4)->nullable()->default('NULL');
                $table->decimal('base_subtotal',20,4)->nullable()->default('NULL');
                $table->decimal('grand_total',20,4)->nullable()->default('NULL');
                $table->decimal('total_paid',20,4)->nullable()->default('NULL');
                $table->decimal('subtotal',20,4)->nullable()->default('NULL');
                $table->decimal('base_subtotal_incl_tax',20,4)->nullable()->default('NULL');

                //refund
                $table->decimal('total_refunded',20,4)->nullable()->default('NULL');
                $table->integer('payplug_payments_installment_plan_status',11)->nullable()->default('NULL');
                $table->decimal('payplug_payments_total_due',12,4)->nullable()->default('NULL');

                // rewards
                $table->decimal('mp_reward_earn',10,0)->nullable()->default('NULL');
                $table->decimal('mp_reward_spent',10,0)->nullable()->default('NULL');

                // discount
                $table->decimal('base_discount_amount',20,4)->nullable()->default('NULL');          
                $table->decimal('total_due',20,4)->nullable()->default('NULL');

                // items
                $table->decimal('base_total_qty_ordered',12,4)->nullable()->default('NULL');


                // coupon
                $table->string('coupon_code')->nullable()->default('NULL');
                $table->string('coupon_rule_name')->nullable()->default('NULL');



                $table->primary('entity_id');

        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_order');
    }
}