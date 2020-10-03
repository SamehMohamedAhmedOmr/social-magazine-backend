<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToppingMenuLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topping_menu_language', function (Blueprint $table) {
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('topping_menu_id');
            $table->string('name', 512);
            $table->string('slug', 512)->unique()->index();
            $table->primary(['topping_menu_id', 'language_id']);
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade');
            $table->foreign('topping_menu_id')->references('id')
                ->on('topping_menus')->onDelete('cascade');
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
        Schema::dropIfExists('topping_menu_language');
    }
}
