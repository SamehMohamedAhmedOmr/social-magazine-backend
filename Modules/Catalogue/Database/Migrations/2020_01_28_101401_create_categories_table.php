<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 128);

            $table->unsignedInteger('image')->nullable();
            $table->foreign('image')->references('id')
                ->on('gallery')->onDelete('cascade')->onUpdate('cascade');


            $table->unsignedInteger('icon')->nullable();
            $table->foreign('icon')->references('id')
                ->on('gallery')->onDelete('cascade')->onUpdate('cascade');

            $table->boolean('is_active')->default(1);
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')
                ->on('categories')->onDelete('set null');
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
        Schema::dropIfExists('categories');
    }
}
