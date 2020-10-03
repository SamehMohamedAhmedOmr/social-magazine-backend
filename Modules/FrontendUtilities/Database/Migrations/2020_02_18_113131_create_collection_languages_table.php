<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_languages', function (Blueprint $table) {
            // primary / foreign keys
            $table->unsignedInteger('collection_id', false);
            $table->unsignedInteger('language_id', false);
            $table->foreign('collection_id')->references('id')->on('collection')->onDelete('cascade');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->primary(['collection_id' , 'language_id']);
            // Attributes
            $table->string('title')->comment('collection title');
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
        Schema::dropIfExists('collection_languages');
    }
}
