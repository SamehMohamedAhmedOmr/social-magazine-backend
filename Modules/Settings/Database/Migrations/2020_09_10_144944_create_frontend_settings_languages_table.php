<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendSettingsLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_settings_languages', function (Blueprint $table) {
            $table->unsignedInteger('setting_id');
            $table->foreign('setting_id')->references('id')
                ->on('frontend_settings')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade')->onUpdate('cascade');

            $table->string('home_page_title');
            $table->text('home_page_meta_desc');

            $table->primary(['setting_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('frontend_settings_languages');
    }
}
