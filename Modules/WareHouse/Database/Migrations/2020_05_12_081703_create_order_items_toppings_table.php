<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderItemsToppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items_toppings', function (Blueprint $table) {
            $table->unsignedInteger('order_item_id');
            $table->foreign('order_item_id')->references('id')->on('order_items')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('topping_id');
            $table->foreign('topping_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

            $table->double('price')->default(0);

            $table->integer('quantity')->default(0);

            $table->float('buying_price', 20, 3)->default(0);

            $table->primary(['order_item_id','topping_id']);
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
        Schema::dropIfExists('order_items_toppings');
    }
}
