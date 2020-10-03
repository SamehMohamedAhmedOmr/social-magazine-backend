<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductToppingMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_topping_menu', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('topping_menu_id');
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');
            $table->foreign('topping_menu_id')->references('id')
                ->on('topping_menus')->onDelete('cascade');
            $table->primary(['product_id', 'topping_menu_id']);
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
        Schema::dropIfExists('product_topping_menu');
    }
}
