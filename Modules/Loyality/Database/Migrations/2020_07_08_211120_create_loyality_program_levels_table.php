<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyalityProgramLevelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyality_program_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('points');
            $table->unsignedInteger('loyality_program_id');
            $table->foreign('loyality_program_id')->references('id')->on('loyality_programs')
                ->onDelete('cascade');
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
        Schema::dropIfExists('loyality_program_levels');
    }
}
