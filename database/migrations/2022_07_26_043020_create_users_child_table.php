<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersChildTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_child', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->default(0)->index('FK_tenants_child_users_3');
            $table->string('child_name')->nullable();
            $table->string('new_ic', 50)->nullable();
            $table->string('birth_cert', 50)->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_tenants_child_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_tenants_child_users_2');
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
        Schema::dropIfExists('users_child');
    }
}
