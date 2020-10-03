<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners_languages', function (Blueprint $table) {
            // primary / foreign keys
            $table->unsignedInteger('banner_id');
            $table->unsignedInteger('language_id');
            // foreign key on banner table , lang table
            $table->foreign('banner_id')->references('id')
                ->on('banners')->onDelete('cascade');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade');
            // title
            $table->string('title')->nullable()->comment('banner title');
            $table->text('description')->nullable()->comment('banner description');

            // alternative text in case image broken or not found
            $table->string('alternative', '255')->nullable()
                ->comment('banner alternative text in case image is broken');
            // Business Rules [ Maximum two Subjects ]
            $table->text('subject_1')->nullable()->comment('banner first subject (optional)');
            $table->text('subject_2')->nullable()->comment('banner first subject (optional)');
            $table->primary(['banner_id','language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banners_languages');
    }
}
