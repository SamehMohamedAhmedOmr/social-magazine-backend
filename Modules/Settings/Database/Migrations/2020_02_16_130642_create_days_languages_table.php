<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDaysLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('days_languages', function (Blueprint $table) {
            // primary / foreign keys
            $table->unsignedInteger('day_id', false);
            $table->unsignedInteger('language_id', false);

            // foreign key on banner table , lang table
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->primary(['day_id' , 'language_id']);
            // properties
            $table->string('name')->comment('day name');

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
        Schema::dropIfExists('days_languages');
    }
}
