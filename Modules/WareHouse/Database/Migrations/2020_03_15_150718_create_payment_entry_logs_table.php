<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentEntryLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_payment_entry_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('purchase_payment_entry');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('payment_entry_id');
            $table->foreign('payment_entry_id')->references('id')->on('payment_entries')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->string('log_type');
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
        Schema::dropIfExists('purchase_payment_entry_logs');
    }
}
