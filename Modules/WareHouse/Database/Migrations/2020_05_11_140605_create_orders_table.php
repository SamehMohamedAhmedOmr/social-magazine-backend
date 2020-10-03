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
            $table->increments('id');

            $table->boolean('status')->default(0)
                ->comment('0 => Pending/ 1 => in shipping/ 2 => cancelled/ 3 => confirmed/ 4 => delivered');

            $table->boolean('is_autoship')->default(0);

            $table->date('delivery_date');

            $table->double('total_price')->default(0);

            $table->double('shipping_price')->default(0);

            $table->double('discount')->default(0);

            $table->double('vat')->default(0);


            $table->boolean('is_pickup')->default(0);

            $table->boolean('is_restored')->default(0);

            $table->boolean('is_active')->default(0);

            $table->text('cancellation_reason')->nullable();

            $table->string('device_id', 128)->nullable()->comment('device_id for mobile and MAC address for web');
            $table->string('device_os', 128)->nullable();
            $table->string('app_version', 128)->nullable();

            $table->float('loyality_discount')->default(0);

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('address_id');
            $table->foreign('address_id')->references('id')->on('address')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('warehouse_id')->nullable();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('time_section_id')->nullable();
            $table->foreign('time_section_id')->references('id')->on('time_sections')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('shipping_rule_id')->nullable();
            $table->foreign('shipping_rule_id')->references('id')->on('shipping_rules')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('external_sales_order_id')->nullable();
            //   $table->foreign('external_sales_order_id')->references('id')->on('external_sales_orders')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('payment_order_id')->nullable();
            //   $table->foreign('payment_order_id')->references('id')->on('payment_orders')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();

            $table->softDeletes();
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
