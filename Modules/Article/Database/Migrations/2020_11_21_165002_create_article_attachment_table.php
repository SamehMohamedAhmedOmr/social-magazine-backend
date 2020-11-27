<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_attachment', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');

            $table->string('file');

            $table->unsignedInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')
                ->on('articles')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('status_id')->nullable();
            $table->foreign('status_id')->references('id')
                ->on('article_status_list')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('attachment_type_id')->nullable();
            $table->foreign('attachment_type_id')->references('id')
                ->on('attachments_type')->onDelete('set null')->onUpdate('cascade');

            $table->unsignedInteger('uploaded_by')->nullable();
            $table->foreign('uploaded_by')->references('id')
                ->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->text('description')->nullable();

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
        Schema::dropIfExists('article_attachment');
    }
}
