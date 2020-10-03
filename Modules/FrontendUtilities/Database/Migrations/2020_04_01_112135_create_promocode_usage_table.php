<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodeUsageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocode_usage', function (Blueprint $table) {
            $table->increments('id');
            $table->float('discount', 10, 2);
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('sales_order_id')->nullable();
            $table->unsignedInteger('promocode_id');
            $table->unsignedInteger('user_id');

            $table->foreign('promocode_id')->references('id')->on('promocodes');
            $table->foreign('user_id')->references('id')->on('users');

            /**
             * TODO uncomment whe orders & sales_orders are implemented
             */
            // $table->foreign('order_id')->references('id')->on('orders');
            // $table->foreign('sales_order_id')->references('id')->on('sales_orders');
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
        Schema::dropIfExists('promocode_usage');
    }
}
