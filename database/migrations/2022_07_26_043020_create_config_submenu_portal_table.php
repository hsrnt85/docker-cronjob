<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSubmenuPortalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_submenu_portal', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_menu_portal_id')->nullable()->index('FK_config_submenu_portal_config_menu_portal');
            $table->string('submenu')->nullable();
            $table->tinyInteger('level')->nullable()->default(1);
            $table->string('route_name')->nullable();
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
        Schema::dropIfExists('config_submenu_portal');
    }
}
