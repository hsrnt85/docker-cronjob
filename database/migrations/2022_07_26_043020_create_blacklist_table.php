<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlacklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blacklist', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('range')->nullable();
            $table->boolean('data_status')->nullable()->default(true);
            $table->integer('action_by')->nullable();
            $table->dateTime('action_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blacklist');
    }
}
