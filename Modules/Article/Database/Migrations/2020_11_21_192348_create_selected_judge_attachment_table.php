<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSelectedJudgeAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selected_judge_attachment', function (Blueprint $table) {

            $table->unsignedInteger('selected_judge_id')->nullable();
            $table->foreign('selected_judge_id')->references('id')
                ->on('article_selected_judge')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('attachment_id')->nullable();
            $table->foreign('attachment_id')->references('id')
                ->on('article_attachment')->onDelete('set null')->onUpdate('cascade');

            $table->primary(['selected_judge_id','attachment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('selected_judge_attachment');
    }
}
