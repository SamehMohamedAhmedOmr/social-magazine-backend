<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_sections', function (Blueprint $table) {
            $table->increments('id');
            $table->time('from')
                ->comment('time to deliver the order (from)');
            $table->time('to')
                ->comment('time to deliver the order (to)');
            $table->boolean('is_active')->default(1)
                ->comment('active = 1 , inactive = 0');

            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_sections');
    }
}
