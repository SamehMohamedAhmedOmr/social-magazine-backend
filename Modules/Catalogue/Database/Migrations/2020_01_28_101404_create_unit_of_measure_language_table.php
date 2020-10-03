<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitOfMeasureLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit_of_measure_language', function (Blueprint $table) {
            $table->string('name', 128);
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('unit_of_measure_id');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade');
            $table->foreign('unit_of_measure_id')->references('id')
                ->on('units_of_measure')->onDelete('cascade');
            $table->primary(['language_id', 'unit_of_measure_id']);
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
        Schema::dropIfExists('unit_of_measure_language');
    }
}
