<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('purchase_receipt_id');
            $table->foreign('purchase_receipt_id')->references('id')
                ->on('purchase_receipts')->onDelete('cascade')->onUpdate('cascade');

            $table->float('total_price', 10, 2);
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
        Schema::dropIfExists('purchase_invoices');
    }
}
