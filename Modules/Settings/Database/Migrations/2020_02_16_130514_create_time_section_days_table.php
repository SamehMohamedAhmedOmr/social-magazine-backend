<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeSectionDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_section_days', function (Blueprint $table) {
            $table->unsignedInteger('day_id', false);
            $table->unsignedInteger('time_section_id', false);

            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->foreign('time_section_id')->references('id')->on('time_sections')->onDelete('cascade');
            $table->primary(['day_id' , 'time_section_id']);
            $table->softDeletes();
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
        Schema::dropIfExists('time_section_days');
    }
}
