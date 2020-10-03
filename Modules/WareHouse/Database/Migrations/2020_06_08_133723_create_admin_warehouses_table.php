<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_warehouses', function (Blueprint $table) {
            $table->unsignedInteger('admin_id');
            $table->foreign('admin_id')->references('id')
                ->on('admin')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')
                ->on('warehouses')->onDelete('cascade')->onUpdate('cascade');

            $table->primary(['admin_id','warehouse_id']);

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
        Schema::dropIfExists('admin_warehouses');
    }
}
