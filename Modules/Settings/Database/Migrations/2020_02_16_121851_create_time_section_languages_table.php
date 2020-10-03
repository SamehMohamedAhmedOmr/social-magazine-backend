<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeSectionLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_section_languages', function (Blueprint $table) {
            // primary / foreign keys
            $table->unsignedInteger('time_section_id', false);
            $table->unsignedInteger('language_id', false);

            $table->foreign('time_section_id')->references('id')->on('time_sections')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->primary(['time_section_id' , 'language_id']);
            // properties
            $table->string('name')->comment('time section name');

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
        Schema::dropIfExists('time_section_languages');
    }
}
