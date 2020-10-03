<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('currency_code');
            $table->foreign('currency_code')->references('id')
                ->on('currency')->onDelete('cascade')->onUpdate('cascade');
            $table->string('price_list_name');
            $table->unsignedInteger('type');
            $table->foreign('type')->references('id')
                ->on('price_list_types')->onDelete('cascade')->onUpdate('cascade');
            $table->string('key')->unique();
            $table->boolean('is_special')
                ->comment('This boolean is indicates whether it is the main selling price or the main buying price');
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('price_lists');
    }
}
