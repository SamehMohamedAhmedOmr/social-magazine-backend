<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMagazineSocialMedia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magazine_information', function (Blueprint $table) {
            $table->string('facebook')->after('magazine_link')->nullable();
            $table->string('twitter')->after('facebook')->nullable();
            $table->string('instgram')->after('twitter')->nullable();
            $table->string('whatsapp')->after('instgram')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
