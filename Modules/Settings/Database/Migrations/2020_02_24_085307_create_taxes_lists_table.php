<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxesListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxes_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->float('price', 10, 2);
            $table->boolean('is_active')->default(1)->comment('1 active, 0 inactive');

            $table->unsignedInteger('tax_type_id')->comment('Define the type of tax Ex: On total after shipping');
            $table->foreign('tax_type_id')->references('id')->on('taxes_types')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedInteger('tax_amount_type_id')->comment('Define the amount type of tax Ex: Fixed / Percentage');
            $table->foreign('tax_amount_type_id')->references('id')->on('taxes_amount_types')->onDelete('cascade')->onUpdate('cascade');

            $table->string('key');

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
        Schema::dropIfExists('taxes_lists');
    }
}
