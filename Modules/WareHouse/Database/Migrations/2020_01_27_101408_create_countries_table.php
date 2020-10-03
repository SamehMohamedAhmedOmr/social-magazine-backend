<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            // primary key
            $table->increments('id');
            // properties
            $table->string('country_code', '5')->unique()
                ->comment('unique country code');
            $table->boolean('is_active')->default(1)
                ->comment(' active record 1 , inactive record 0');

            $table->string('image')->comment('Country Main Image');
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
        Schema::dropIfExists('countries');
    }
}
