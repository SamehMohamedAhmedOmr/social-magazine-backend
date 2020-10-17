<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagazineInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magazine_information', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('address')->nullable();

            $table->string('phone')->nullable();
            $table->string('fax_number')->nullable();

            $table->string('email')->nullable();
            $table->string('postal_code')->nullable();
            $table->bigInteger('visitor_number')->default(0);
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
        Schema::dropIfExists('magazine_information');
    }
}
