<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToConfigSubmenuPortalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config_submenu_portal', function (Blueprint $table) {
            $table->foreign(['config_menu_portal_id'], 'FK_config_submenu_portal_config_menu_portal')->references(['id'])->on('config_menu_portal')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_submenu_portal', function (Blueprint $table) {
            $table->dropForeign('FK_config_submenu_portal_config_menu_portal');
        });
    }
}
