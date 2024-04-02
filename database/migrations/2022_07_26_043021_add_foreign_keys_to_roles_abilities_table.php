<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToRolesAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles_abilities', function (Blueprint $table) {
            $table->foreign(['roles_id'], 'FK_roles_abilities_role')->references(['id'])->on('roles')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['config_submenu_id'], 'FK_roles_abilities_config_submenu')->references(['id'])->on('config_submenu')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['config_menu_id'], 'FK_roles_abilities_config_menu')->references(['id'])->on('config_menu')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles_abilities', function (Blueprint $table) {
            $table->dropForeign('FK_roles_abilities_role');
            $table->dropForeign('FK_roles_abilities_config_submenu');
            $table->dropForeign('FK_roles_abilities_config_menu');
        });
    }
}
