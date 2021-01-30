<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleSelectedJudgeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_selected_judge', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')
                ->on('articles')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('judge_id')->nullable();
            $table->foreign('judge_id')->references('id')
                ->on('users')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('recommendation_id')->nullable();
            $table->foreign('recommendation_id')->references('id')
                ->on('referees_recommendation')->onDelete('set null')->onUpdate('cascade');


            $table->text('author_note')->nullable();
            $table->text('magazine_note')->nullable();

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
        Schema::dropIfExists('article_selected_judge');
    }
}
