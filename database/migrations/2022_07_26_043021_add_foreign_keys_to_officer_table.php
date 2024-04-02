<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToOfficerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('officer', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_officer_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['users_id'], 'FK_officer_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_officer_users_3')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['district_id'], 'FK_officer_district')->references(['id'])->on('district')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('officer', function (Blueprint $table) {
            $table->dropForeign('FK_officer_users_2');
            $table->dropForeign('FK_officer_users');
            $table->dropForeign('FK_officer_users_3');
            $table->dropForeign('FK_officer_district');
        });
    }
}
