<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMobileUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_updates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('device_type');
            $table->string('application_version', 32);
            $table->smallInteger('build_number');
            $table->boolean('is_active')->default(1)->comment('1 is active, 0 is not active');
            $table->boolean('force_update')->default(0)->comment('0 not force, 1 is force');
            $table->date('release_date');
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
        Schema::dropIfExists('mobile_updates');
    }
}
