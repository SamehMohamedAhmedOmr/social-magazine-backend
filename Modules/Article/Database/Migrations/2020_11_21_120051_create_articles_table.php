<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');

            $table->string('article_code')->unique();
            $table->string('title_ar');
            $table->string('title_en');
            $table->string('slug');

            $table->string('content_ar');
            $table->string('content_en');

            $table->unsignedInteger('article_subject_id')->nullable();
            $table->foreign('article_subject_id')->references('id')
                ->on('article_subject')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('author_id')->nullable();
            $table->foreign('author_id')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('article_type_id')->nullable();
            $table->foreign('article_type_id')->references('id')
                ->on('article_type')->onDelete('set null')->onUpdate('cascade');

            $table->date('review_date')->nullable();
            $table->date('acceptance_date')->nullable();

            $table->json('keywords_en')->nullable();
            $table->json('keywords_ar')->nullable();

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
        Schema::dropIfExists('articles');
    }
}
