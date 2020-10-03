<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesLayoutTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_layout', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('layout_type_id');
            $table->foreign('layout_type_id')->references('id')
                ->on('pages_layout_types')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('name');
            $table->string('key');

            $table->string('value')->nullable();

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
        Schema::dropIfExists('pages_layout');
    }
}
