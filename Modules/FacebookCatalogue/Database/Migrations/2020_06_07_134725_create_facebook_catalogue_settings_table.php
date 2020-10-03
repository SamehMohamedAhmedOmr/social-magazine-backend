<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookCatalogueSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_catalogue_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('android_package_name')
                ->default('com.'. strtolower(env('APP_NAME')) .'.android');
            $table->string('android_fallback_link')
                ->nullable();
            $table->string('android_min_package_version_code')
                ->nullable();
            $table->string('ios_bundle_id')
                ->default('com.'. strtolower(env('APP_NAME')) .'.ios');
            $table->string('ios_fallback_link')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facebook_catalogue_settings');
    }
}
