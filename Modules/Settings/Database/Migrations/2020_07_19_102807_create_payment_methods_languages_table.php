<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentMethodsLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods_languages', function (Blueprint $table) {

            $table->unsignedInteger('payment_method_id');
            $table->foreign('payment_method_id')->references('id')
                ->on('payment_methods')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')
                ->on('languages')->onDelete('cascade')->onUpdate('cascade');


            $table->string('name', 128);

            $table->primary(['payment_method_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods_languages');
    }
}
