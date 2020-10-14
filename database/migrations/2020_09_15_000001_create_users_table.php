<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('username');
            $table->string('passcode');
            $table->string('passcodeConfirm');
            $table->dateTime('passcodeChangeAt');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->unsignedBigInteger('pointCollected');
            $table->unsignedBigInteger('pointUsable');
        });
    }

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
