<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendTypographyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_typography', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('main_font')->nullable();
            $table->foreign('main_font')->references('id')
                ->on('fonts')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('bold_font')->nullable();
            $table->foreign('bold_font')->references('id')
                ->on('fonts')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('regular_font')->nullable();
            $table->foreign('regular_font')->references('id')
                ->on('fonts')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('italic_font')->nullable();
            $table->foreign('italic_font')->references('id')
                ->on('fonts')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('frontend_setting_id');
            $table->foreign('frontend_setting_id')->references('id')
                ->on('frontend_settings')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('frontend_typography');
    }
}
