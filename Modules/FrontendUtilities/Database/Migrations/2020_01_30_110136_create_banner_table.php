<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->increments('id');
            // href when click on banner
            $table->text('link')->comment('banner link on click on it')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('image')->nullable();
            $table->foreign('image')->references('id')
                ->on('gallery')->onDelete('cascade')->onUpdate('cascade');

            // enable or disable banner on different platforms
            $table->boolean('enable_ios')->default(0)->comment('enable banner on ios devices');
            $table->boolean('enable_android')->default(0)->comment('enable banner on android devices');
            $table->boolean('enable_web')->default(0)->comment('enable banner on web devices');
            $table->tinyInteger('order')->default(0);
            $table->boolean('is_active')->default(1)->comment(' active record 1 , inactive record 0');
            // timestamp
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
        Schema::dropIfExists('banners');
    }
}
