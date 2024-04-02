<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartersInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarters_inventory', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('q_id')->nullable()->index('FK_quarters_inventory_quarters');
            $table->integer('i_id')->nullable()->index('FK_quarters_inventory_inventory');
            $table->integer('quantity')->nullable();
            $table->integer('m_inventory_id')->nullable()->index('FK_quarters_inventory_maintenance_inventory');
            $table->tinyInteger('data_status')->nullable();
            $table->integer('action_by')->nullable()->index('FK_quarters_inventory_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_quarters_inventory_users_2');
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
        Schema::dropIfExists('quarters_inventory');
    }
}
