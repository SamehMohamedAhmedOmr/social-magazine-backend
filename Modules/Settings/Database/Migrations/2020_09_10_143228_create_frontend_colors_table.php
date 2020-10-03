<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('main_color')->nullable();
            $table->string('second_color')->nullable();
            $table->string('third_color')->nullable();

            $table->unsignedInteger('frontend_setting_id');
            $table->foreign('frontend_setting_id')->references('id')
                ->on('frontend_settings')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('frontend_colors');
    }
}
