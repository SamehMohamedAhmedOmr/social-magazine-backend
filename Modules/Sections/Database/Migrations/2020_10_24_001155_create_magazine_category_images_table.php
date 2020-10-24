<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagazineCategoryImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazine_category_images', function (Blueprint $table) {
            $table->unsignedInteger('category_id');
            $table->foreign('category_id')->references('id')
                ->on('magazine_category')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')
                ->on('gallery')->onDelete('restrict')->onUpdate('cascade');

            $table->primary(['category_id','image_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magazine_category_images');
    }
}
