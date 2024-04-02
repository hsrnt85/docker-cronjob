<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToQuartersCatMaintenanceFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters_cat_maintenance_fee', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_quarters_cat_maintenance_fee_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['q_cat_id'], 'FK_quarters_cat_maintenance_fee_quarters_category')->references(['id'])->on('quarters_category');
            $table->foreign(['delete_by'], 'FK_quarters_cat_maintenance_fee_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['m_fee_id'], 'FK_quarters_cat_maintenance_fee_maintenance_fee')->references(['id'])->on('maintenance_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters_cat_maintenance_fee', function (Blueprint $table) {
            $table->dropForeign('FK_quarters_cat_maintenance_fee_users');
            $table->dropForeign('FK_quarters_cat_maintenance_fee_quarters_category');
            $table->dropForeign('FK_quarters_cat_maintenance_fee_users_2');
            $table->dropForeign('FK_quarters_cat_maintenance_fee_maintenance_fee');
        });
    }
}
