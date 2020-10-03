<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection', function (Blueprint $table) {
            $table->increments('id');
            // Attributes
            $table->boolean('enable_ios')->default(1)
                ->comment('enable collection on ios devices');
            $table->boolean('enable_android')->default(1)
                ->comment('enable collection on android devices');
            $table->boolean('enable_web')->default(1)
                ->comment('enable collection on web devices');
            $table->boolean('is_active')->default(1)
                ->comment('1 -> Active , 0 -> InActive , By Default collection is Active');
            $table->tinyInteger('order')
                ->comment('order of the collection');

            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

            $table->softDeletes();
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
        Schema::dropIfExists('collection');
    }
}
