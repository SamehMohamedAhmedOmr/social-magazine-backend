<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_languages', function (Blueprint $table) {
            // primary / foreign keys
            $table->unsignedInteger('district_id', false);
            $table->unsignedInteger('language_id', false);
            // foreign key on district  table , language table
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->primary(['district_id' , 'language_id']);
            // attributes
            $table->string('name')->comment('district name');
            $table->text('description')->comment('district description');
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
        Schema::dropIfExists('district_languages');
    }
}
