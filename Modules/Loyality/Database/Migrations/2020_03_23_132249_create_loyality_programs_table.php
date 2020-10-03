<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyalityProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyality_programs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('price_to_points')->default(0);
            $table->float('point_to_price', 20, 3)->unsigned()->default(0);
            $table->unsignedInteger('max_allowed_points')->default(0);
            $table->unsignedInteger('min_allowed_points')->default(0);
            $table->string('points_option', 32)->default('price')
                ->comment('For purchase if the price to points is on the total price of the products
                or it is a percentage');
            $table->unsignedInteger('days_until_refund')->default(0);
            $table->timestamp('expiration_date')->default(now());
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
        Schema::dropIfExists('loyality_programs');
    }
}
