<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVariantLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variant_language', function (Blueprint $table) {
            $table->unsignedInteger('language_id');
            $table->unsignedInteger('variant_id');
            $table->string('name', 512);
            $table->string('slug', 512)->unique()->index();
            $table->primary(['variant_id', 'language_id']);
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade');
            $table->foreign('variant_id')->references('id')
                ->on('variants')->onDelete('cascade');
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
        Schema::dropIfExists('variant_language');
    }
}
