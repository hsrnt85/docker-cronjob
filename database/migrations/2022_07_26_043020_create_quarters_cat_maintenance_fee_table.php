<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartersCatMaintenanceFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarters_cat_maintenance_fee', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('q_cat_id')->nullable()->index('FK_quarters_cat_maintenance_fee_quarters_category');
            $table->integer('m_fee_id')->nullable()->index('FK_quarters_cat_maintenance_fee_maintenance_fee');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_quarters_cat_maintenance_fee_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_quarters_cat_maintenance_fee_users_2');
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
        Schema::dropIfExists('quarters_cat_maintenance_fee');
    }
}
