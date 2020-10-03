<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemNotesLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system_notes_language', function (Blueprint $table) {
            $table->unsignedInteger('system_note_id');
            $table->foreign('system_note_id')->references('id')->on('system_notes')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade')->onUpdate('cascade');

            $table->text('note_body');

            $table->primary(['system_note_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('system_notes_language');
    }
}
