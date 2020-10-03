<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsumeLevelLoyalityPrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyality_programs', function (Blueprint $table) {
            $table->boolean('is_levels')->default(false)->after('days_until_expiration');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loyality_programs', function (Blueprint $table) {
            $table->dropColumn('is_levels');
        });
    }
}
