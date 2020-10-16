<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_types', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')
                ->on('user_types')->onDelete('restrict')->onUpdate('cascade');

            $table->boolean('main_type')->default(0);

            $table->primary(['user_id','user_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account_types');
    }
}
