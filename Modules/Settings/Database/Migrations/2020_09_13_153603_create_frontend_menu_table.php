<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->integer('order')->default(0);

            $table->unsignedInteger('navigation_type_id');
            $table->foreign('navigation_type_id')
                ->references('id')->on('frontend_menu_navigation_type')
                ->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('frontend_menu');
    }
}
