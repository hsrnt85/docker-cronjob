<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigSubmenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_submenu', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('config_menu_id')->nullable()->index('FK_config_submenu_config_menu');
            $table->string('submenu')->nullable();
            $table->string('route_name')->nullable();
            $table->string('action')->nullable();
            $table->string('folder_path')->nullable();
            $table->integer('order')->nullable();
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
        Schema::dropIfExists('config_submenu');
    }
}
