<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePointsLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('points_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('points_gained')->default(0);
            $table->unsignedInteger('points_redeemed')->default(0);
            $table->float('money_spent')->unsigned()->default(0);
            $table->float('money_saved')->unsigned()->default(0);
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('user_id');
            $table->dateTime('refund_date');
            $table->dateTime('expiration_date');
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
        Schema::dropIfExists('points_logs');
    }
}
