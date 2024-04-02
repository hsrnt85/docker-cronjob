<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToConfigSubmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config_submenu', function (Blueprint $table) {
            $table->foreign(['config_menu_id'], 'FK_config_submenu_config_menu')->references(['id'])->on('config_menu')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_submenu', function (Blueprint $table) {
            $table->dropForeign('FK_config_submenu_config_menu');
        });
    }
}
