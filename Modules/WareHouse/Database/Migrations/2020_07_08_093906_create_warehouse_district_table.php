<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehouseDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_district', function (Blueprint $table) {
            $table->unsignedInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')
                ->on('warehouses')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('district_id');
            $table->foreign('district_id')->references('id')
                ->on('districts')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['warehouse_id' , 'district_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_district');
    }
}
