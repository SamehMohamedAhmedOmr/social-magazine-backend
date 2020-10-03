<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('tacking_id', 1000)
                ->comment('id from the shipment service');
            $table->string('tracking_number', 1000)->nullable()
                ->comment('tracking number from the shipment service');
            $table->string('current_status')->nullable()
                ->comment('nullable because some doesn\'t support that');
            $table->string('receipt', 1000)->nullable();
            $table->unsignedBigInteger('shipping_company_id');
            $table->foreign('shipping_company_id')->references('id')
                ->on('shipping_companies')->onDelete('restrict');
            $table->unsignedBigInteger('order_id');
            // TODO:: add foreign key
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
        Schema::dropIfExists('shipments');
    }
}
