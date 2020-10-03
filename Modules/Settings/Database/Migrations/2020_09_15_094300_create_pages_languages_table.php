<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_languages', function (Blueprint $table) {
            $table->unsignedInteger('page_id');
            $table->foreign('page_id')->references('id')
                ->on('pages')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade')->onUpdate('cascade');

            $table->string('title');
            $table->text('content')->nullable();
            $table->string('seo_title');
            $table->text('seo_description')->nullable();

            $table->primary(['page_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages_languages');
    }
}
