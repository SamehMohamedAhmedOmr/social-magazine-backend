<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            // primary key
            $table->increments('id');
            // foreign keys
            $table->unsignedInteger('country_id');
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade');

            $table->unsignedInteger('shipping_role_id')->nullable();
            $table->foreign('shipping_role_id')->references('id')
                ->on('shipping_rules')->onDelete('cascade');

            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')
                ->on('districts')->onDelete('cascade');
            // properties
            $table->boolean('is_active')->default(1)
                ->comment(' active record 1 , inactive record 0');

            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->tinyInteger('zoom_level')->nullable();

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
        Schema::dropIfExists('districts');
    }
}
