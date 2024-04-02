<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamageAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damage_area', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('damage_area')->nullable();
            $table->integer('data_status')->default(1);
            $table->string('action_by', 50)->default('0');
            $table->string('action_on', 50)->default('0');
            $table->string('delete_by', 50)->default('0');
            $table->string('delete_on', 50)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('damage_area');
    }
}
