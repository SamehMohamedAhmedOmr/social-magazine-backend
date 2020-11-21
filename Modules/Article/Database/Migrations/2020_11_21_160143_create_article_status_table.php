<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_status', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')
                ->on('articles')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')
                ->on('article_status_list')->onDelete('set null')->onUpdate('cascade');

            $table->date('review_date')->nullable();
            $table->date('judgement_date')->nullable();

            $table->unsignedInteger('magazine_director_id')->nullable();
            $table->foreign('magazine_director_id')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('magazine_manager_note')->nullable();

            $table->unsignedInteger('price_type_id')->nullable();
            $table->foreign('price_type_id')->references('id')
                ->on('price_type')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('payment_method_id')->nullable();
            $table->foreign('payment_method_id')->references('id')
                ->on('payment_method')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('currency_type_id')->nullable();
            $table->foreign('currency_type_id')->references('id')
                ->on('currency_type')->onDelete('cascade')->onUpdate('cascade');

            $table->string('fees')->nullable();

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
        Schema::dropIfExists('article_status');
    }
}
