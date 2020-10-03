<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_language', function (Blueprint $table) {
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('product_id');
            $table->string('name', 512);
            $table->text('description')->nullable();
            $table->string('slug', 512)->unique()->index();
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade');
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade');
            $table->primary(['product_id', 'language_id']);
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
        Schema::dropIfExists('product_language');
    }
}
