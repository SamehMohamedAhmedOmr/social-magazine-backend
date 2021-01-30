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

            $table->unsignedInteger('judge_id');
            $table->foreign('judge_id')->references('id')
                ->on('article_selected_judge')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('attachment_id');
            $table->foreign('attachment_id')->references('id')
                ->on('article_attachment')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['judge_id','attachment_id']);
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
