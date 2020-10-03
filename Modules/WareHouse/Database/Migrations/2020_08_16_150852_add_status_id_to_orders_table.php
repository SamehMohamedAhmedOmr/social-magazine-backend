<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusIdToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->unsignedInteger('order_status_id')
                ->default(1)->nullable()->after('id');
            $table->foreign('order_status_id')
                ->references('id')
                ->on('order_statuses')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('order_status_id_foreign');
            $table->dropColumn('order_status_id');
            $table->boolean('status')->default(0)
                ->comment('0 => Pending/ 1 => in shipping/ 2 => cancelled/ 3 => confirmed/ 4 => delivered')
            ->after('id');
        });
    }
}
