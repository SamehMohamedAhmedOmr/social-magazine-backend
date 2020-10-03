<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('image');
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');

            $table->foreign('image')->references('id')
                ->on('gallery');

            $table->integer('order')->nullable();

            $table->primary(['product_id','image']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
