<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_menu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('menu')->nullable();
            $table->integer('flag_dashboard')->nullable()->default(0);
            $table->integer('flag_report')->nullable()->default(0);
            $table->integer('order')->nullable()->default(0);
            $table->tinyInteger('data_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('config_menu');
    }
}
