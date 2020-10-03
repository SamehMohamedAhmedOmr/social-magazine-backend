<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->float('weight', 32, 4)->nullable();
            $table->string('sku', 512)->nullable()->unique()->comment("it is stock keeping unit");
            $table->unsignedInteger('main_category_id');
            $table->unsignedInteger('brand_id')->nullable();
            $table->unsignedInteger('unit_of_measure_id')->nullable();
            $table->boolean('is_topping')->default(0);
            $table->boolean('is_bundle')->default(0);
            $table->boolean('is_active')->default(1);

            $table->boolean('is_sell_with_availability')->default(0);
            $table->unsignedInteger('max_quantity_per_order')->default(0);

            $table->foreign('main_category_id')->references('id')
                ->on('categories')->onDelete('restrict');

            $table->foreign('unit_of_measure_id')->references('id')
                ->on('units_of_measure')->onDelete('restrict');

            $table->foreign('brand_id')->references('id')
                ->on('brands')->onDelete('set null');

            $table->softDeletes();
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
        Schema::dropIfExists('products');
    }
}
