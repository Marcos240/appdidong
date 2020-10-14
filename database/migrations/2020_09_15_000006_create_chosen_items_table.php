<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChosenItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chosen_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idItem');
            $table->unsignedBigInteger('idUser');
            $table->unsignedBigInteger('count');
            $table->foreign('idItem')->references('id')->on('items');
            $table->foreign('idUser')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chosen_items');
    }
}
