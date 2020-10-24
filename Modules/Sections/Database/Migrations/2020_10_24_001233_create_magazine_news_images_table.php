<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagazineNewsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazine_news_images', function (Blueprint $table) {
            $table->unsignedInteger('news_id');
            $table->foreign('news_id')->references('id')
                ->on('magazine_news')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')
                ->on('gallery')->onDelete('restrict')->onUpdate('cascade');

            $table->primary(['news_id', 'image_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('magazine_news_images');
    }
}
