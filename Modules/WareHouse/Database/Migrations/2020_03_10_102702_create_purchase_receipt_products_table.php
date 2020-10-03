<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReceiptProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receipt_products', function (Blueprint $table) {
            $table->unsignedInteger('purchase_receipt_id');
            $table->foreign('purchase_receipt_id')->references('id')->on('purchase_receipts')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('requested_quantity');
            $table->integer('remaining_quantity');
            $table->integer('accepted_quantity');


            $table->primary(['purchase_receipt_id','product_id']);

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
        Schema::dropIfExists('purchase_receipt_products');
    }
}
