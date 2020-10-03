<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeExpirationDateLoyalityProgramsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loyality_programs', function (Blueprint $table) {
            $table->dropColumn('expiration_date');
            $table->unsignedInteger('days_until_expiration')->default(1);
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
            $table->timestamp('expiration_date')->default(now());
            $table->dropColumn('days_until_expiration');
        });
    }
}
