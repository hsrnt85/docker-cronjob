<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersSpouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_spouse', function (Blueprint $table) {
            $table->foreign(['users_id'], 'FK_tenant_spouse_users_3')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_tenant_spouse_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_tenant_spouse_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_spouse', function (Blueprint $table) {
            $table->dropForeign('FK_tenant_spouse_users_3');
            $table->dropForeign('FK_tenant_spouse_users_2');
            $table->dropForeign('FK_tenant_spouse_users');
        });
    }
}
