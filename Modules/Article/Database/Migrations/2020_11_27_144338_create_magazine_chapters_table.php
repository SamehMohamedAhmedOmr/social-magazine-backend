<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagazineChaptersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazine_chapters', function (Blueprint $table) {
            $table->increments('id');

            $table->text('title');
            $table->text('slug');

            $table->unsignedInteger('book_id');
            $table->foreign('book_id')->references('id')
                ->on('magazine_books')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('magazine_chapters');
    }
}
