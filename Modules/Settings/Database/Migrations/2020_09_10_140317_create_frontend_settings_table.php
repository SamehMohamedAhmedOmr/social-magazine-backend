<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFrontendSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('frontend_settings', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('favicon')->nullable();
            $table->foreign('favicon')->references('id')
                ->on('gallery')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('logo')->nullable();
            $table->foreign('logo')->references('id')
                ->on('gallery')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('social_sharing_img')->nullable();
            $table->foreign('social_sharing_img')->references('id')
                ->on('gallery')->onDelete('cascade')->onUpdate('cascade');

            $table->string('facebook_pixel_id')->nullable();

            $table->string('google_analytics_id')->nullable();

            $table->boolean('enable_recaptcha')->default(false);

            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('frontend_settings');
    }
}
