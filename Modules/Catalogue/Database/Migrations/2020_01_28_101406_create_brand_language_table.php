<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brand_language', function (Blueprint $table) {
            $table->string('name', 512);
            $table->string('slug', 512)->unique();
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('brand_id');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')
                ->on('brands')->onDelete('cascade');
            $table->primary(['language_id', 'brand_id']);
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
        Schema::dropIfExists('brand_language');
    }
}
