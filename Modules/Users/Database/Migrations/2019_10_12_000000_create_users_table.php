<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');

            $table->string('first_name');
            $table->string('family_name');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('alternative_email')->nullable();
            $table->dateTime('token_last_renew')->nullable();

            $table->boolean('is_active')->default(1);

            $table->unsignedInteger('gender_id'); // male / female
            $table->foreign('gender_id')->references('id')
                ->on('genders')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('title_id')->nullable(); // Mr, Ms , etc
            $table->foreign('title_id')->references('id')
                ->on('titles')->onDelete('restrict')->onUpdate('cascade');


            $table->unsignedInteger('educational_level_id')->nullable(); // دكتوره / ماجستير
            $table->foreign('educational_level_id')->references('id')
                ->on('educational_levels')->onDelete('restrict')->onUpdate('cascade');

            $table->unsignedInteger('educational_degree_id')->nullable(); // أستاذ / أستاذ مشارك / أستاذ مساعد
            $table->foreign('educational_degree_id')->references('id')
                ->on('educational_degrees')->onDelete('restrict')->onUpdate('cascade');

            $table->text('educational_field')->nullable();

            $table->string('university')->nullable();
            $table->string('faculty')->nullable();

            $table->string('phone_number')->nullable();
            $table->string('fax_number')->nullable();

            $table->text('address')->nullable();

            $table->unsignedInteger('country_id')->nullable(); // أستاذ / أستاذ مشارك / أستاذ مساعد
            $table->foreign('country_id')->references('id')
                ->on('countries')->onDelete('restrict')->onUpdate('cascade');

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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('users');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
