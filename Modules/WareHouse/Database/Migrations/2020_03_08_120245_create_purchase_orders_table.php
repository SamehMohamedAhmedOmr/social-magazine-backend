<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');

            $table->date('delivery_date');

            $table->boolean('discount_type')->comment('0 is Fixed , 1 is percentage');
            $table->float('discount', 10, 2);

            $table->float('total_price', 10, 2);

            $table->unsignedInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')
                ->on('warehouses')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('company_id');
            $table->foreign('company_id')->references('id')
                ->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('shipping_rule_id')->nullable();
            $table->foreign('shipping_rule_id')->references('id')
                ->on('shipping_rules')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('tax_id')->nullable();
            $table->foreign('tax_id')->references('id')->on('taxes_lists')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('is_active')->default(1);

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
        Schema::dropIfExists('purchase_orders');
    }
}
