<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendMenuLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_menu_languages', function (Blueprint $table) {
            $table->unsignedInteger('menu_id');
            $table->foreign('menu_id')->references('id')
                ->on('frontend_menu')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');

            $table->primary(['menu_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frontend_menu_languages');
    }
}
