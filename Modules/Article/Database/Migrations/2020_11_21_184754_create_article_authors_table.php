<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_authors', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name');
            $table->string('family_name');

            $table->string('email')->unique();
            $table->string('alternative_email')->nullable();

            $table->unsignedInteger('gender_id'); // male / female
            $table->foreign('gender_id')->references('id')
                ->on('genders')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('title_id')->nullable(); // Mr, Ms , etc
            $table->foreign('title_id')->references('id')
                ->on('titles')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('educational_level_id')->nullable(); // دكتوره / ماجستير
            $table->foreign('educational_level_id')->references('id')
                ->on('educational_levels')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('educational_degree_id')->nullable(); // أستاذ / أستاذ مشارك / أستاذ مساعد
            $table->foreign('educational_degree_id')->references('id')
                ->on('educational_degrees')->onDelete('restrict')->onUpdate('cascade');

            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();

            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')
                ->on('articles')->onDelete('set null')->onUpdate('cascade');

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
        Schema::dropIfExists('article_authors');
    }
}
