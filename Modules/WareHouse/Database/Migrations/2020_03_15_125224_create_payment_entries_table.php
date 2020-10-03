<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_entries', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('purchase_invoice_id');
            $table->foreign('purchase_invoice_id')->references('id')->on('purchase_invoices')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->float('payment_price', 10, 2);
            $table->string('payment_reference')->nullable()->comment('may be Cheque number');

            $table->unsignedInteger('payment_entry_type_id');
            $table->foreign('payment_entry_type_id')->references('id')->on('payment_entry_types')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->tinyInteger('status')->default('0')
                ->comment('0 = Added, 1 = Submitted, 2 = Cancelled');

            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('payment_entries');
    }
}
