<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('product_id');
            $table->foreign('product_id')->references('id')
                ->on('products')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('from_warehouse')->nullable();
            $table->foreign('from_warehouse')->references('id')
                ->on('warehouses')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('to_warehouse');
            $table->foreign('to_warehouse')->references('id')
                ->on('warehouses')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('stock_quantity')->comment('record the quantity that added in stock, will not to be updated');

            $table->boolean('type')->comment('0 => ADDED OR 1=> MOVED');
            $table->boolean('is_active')->default(1)->comment('1 => active, 0 inactive');

            $table->unsignedInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')
                ->on('purchase_orders')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('set null');
            $table->string('file_name')->nullable();

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
        Schema::dropIfExists('stocks');
    }
}
