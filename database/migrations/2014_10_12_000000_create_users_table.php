<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('phone_number');
            $table->string('country_code');
            $table->timestamp('birthday');
            $table->string('password');
            $table->rememberToken();
            $table->string('authy_status')->default('unverified');
            $table->string('authy_id')->nullable();
            $table->timestamps();
        });
    }

    /*
             Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password', 60);
            $table->string('country_code');
            $table->string('phone_number');
            $table->string('authy_status')->default('unverified');
            $table->string('authy_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

     */

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
