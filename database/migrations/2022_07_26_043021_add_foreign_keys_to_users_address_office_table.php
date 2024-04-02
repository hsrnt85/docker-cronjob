<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersAddressOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users_address_office', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_users_address_office_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['users_id'], 'FK_users_address_office_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['district_id'], 'FK_users_address_office_district')->references(['id'])->on('district')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users_address_office', function (Blueprint $table) {
            $table->dropForeign('FK_users_address_office_users_2');
            $table->dropForeign('FK_users_address_office_users');
            $table->dropForeign('FK_users_address_office_district');
        });
    }
}
