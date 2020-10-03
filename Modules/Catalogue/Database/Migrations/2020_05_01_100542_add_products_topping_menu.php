<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductsToppingMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('topping_menu_id')->after('brand_id')->nullable();
            $table->foreign('topping_menu_id')->references('id')
                ->on('topping_menus')->onDelete('set null');

            $table->unsignedInteger('parent_id')->after('topping_menu_id')->nullable();
            $table->foreign('parent_id')->references('id')
                ->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
