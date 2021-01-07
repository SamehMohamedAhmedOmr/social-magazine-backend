<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhotosImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('photos_images', function (Blueprint $table) {
            $table->unsignedInteger('photo_id');
            $table->foreign('photo_id')->references('id')
                ->on('photos')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')
                ->on('gallery')->onDelete('restrict')->onUpdate('cascade');

            $table->primary(['photo_id', 'image_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('photos_images');
    }
}
