<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesAbilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles_abilities', function (Blueprint $table) {
            $table->integer('roles_id')->default(0)->index('FK_roles_abilities_role');
            $table->integer('config_menu_id')->nullable()->index('FK_roles_abilities_config_menu');
            $table->integer('config_submenu_id')->nullable()->index('FK_roles_abilities_config_submenu');
            $table->string('abilities', 50)->nullable()->comment('V-A-U-D-E -> View, Add, Update, Delete, Email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles_abilities');
    }
}
