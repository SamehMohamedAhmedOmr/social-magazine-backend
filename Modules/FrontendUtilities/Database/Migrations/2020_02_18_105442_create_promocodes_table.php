<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromocodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promocodes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->unique();
            $table->float('minimum_price', 10, 2);
            $table->float('maximum_price', 10, 2);
            $table->boolean('is_free_shipping')->nullable()->comment('0 not free, 1 free');
            $table->boolean('discount_type')->nullable()->comment('0 => fixed, 1 => percentage');
            $table->unsignedInteger('users_count');

            $table->float('reward', 10, 2);
            $table->float('max_discount_amount', 10, 2)->nullable();

            $table->unsignedInteger('usage_per_user')->default(1);
            $table->unsignedInteger('total_usage_count')->default(0);

            $table->dateTime('from');
            $table->dateTime('to');
            $table->boolean('is_active');

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
        Schema::dropIfExists('promocodes');
    }
}
