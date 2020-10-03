<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemToppingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_item_toppings', function (Blueprint $table) {
            $table->unsignedInteger('cart_item_id');
            $table->foreign('cart_item_id')->references('id')->on('cart_items')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('topping_id');
            $table->foreign('topping_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['cart_item_id','topping_id']);
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
        Schema::dropIfExists('cart_item_toppings');
    }
}
