<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('idSize');
            $table->unsignedBigInteger('cost');
            $table->string('description');
            $table->string('avatar');
            $table->unsignedBigInteger('idCategory');
            $table->unsignedBigInteger('liked');
            $table->foreign('idCategory')->references('id')->on('categories');
            $table->foreign('idSize')->references('id')->on('sizes');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
