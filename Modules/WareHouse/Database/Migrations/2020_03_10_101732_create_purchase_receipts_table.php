<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_receipts', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('purchase_order_id');
            $table->foreign('purchase_order_id')->references('id')
                ->on('purchase_orders')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('shipping_rule_id')->nullable();
            $table->foreign('shipping_rule_id')->references('id')
                ->on('shipping_rules')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')
                ->on('taxes_lists')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('purchase_receipts');
    }
}
