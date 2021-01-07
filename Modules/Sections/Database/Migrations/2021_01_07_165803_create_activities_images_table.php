<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities_images', function (Blueprint $table) {
            $table->unsignedInteger('activity_id');
            $table->foreign('activity_id')->references('id')
                ->on('activities')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('image_id');
            $table->foreign('image_id')->references('id')
                ->on('gallery')->onDelete('restrict')->onUpdate('cascade');

            $table->primary(['activity_id', 'image_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities_images');
    }
}
