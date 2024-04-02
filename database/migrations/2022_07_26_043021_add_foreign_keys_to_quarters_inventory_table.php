<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToQuartersInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters_inventory', function (Blueprint $table) {
            $table->foreign(['q_id'], 'FK_quarters_inventory_quarters')->references(['id'])->on('quarters')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_quarters_inventory_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['m_inventory_id'], 'FK_quarters_inventory_maintenance_inventory')->references(['id'])->on('maintenance_inventory')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_quarters_inventory_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['i_id'], 'FK_quarters_inventory_inventory')->references(['id'])->on('inventory')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters_inventory', function (Blueprint $table) {
            $table->dropForeign('FK_quarters_inventory_quarters');
            $table->dropForeign('FK_quarters_inventory_users_2');
            $table->dropForeign('FK_quarters_inventory_maintenance_inventory');
            $table->dropForeign('FK_quarters_inventory_users');
            $table->dropForeign('FK_quarters_inventory_inventory');
        });
    }
}
