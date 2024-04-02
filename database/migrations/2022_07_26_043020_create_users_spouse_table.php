<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersSpouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_spouse', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->default(0)->index('FK_tenant_spouse_users_3');
            $table->string('spouse_name')->nullable();
            $table->string('new_ic', 50)->nullable();
            $table->string('old_ic', 50)->nullable();
            $table->string('department_name')->nullable();
            $table->string('position_name')->nullable();
            $table->string('phone_no_hp', 50)->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_tenant_spouse_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_tenant_spouse_users_2');
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
        Schema::dropIfExists('users_spouse');
    }
}
