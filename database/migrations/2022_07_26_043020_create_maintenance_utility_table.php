<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceUtilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_utility', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name')->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable();
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable();
            $table->dateTime('delete_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('maintenance_utility');
    }
}
