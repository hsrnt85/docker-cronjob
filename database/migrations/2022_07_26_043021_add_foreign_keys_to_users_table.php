<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['position_grade_id'], 'FK_users_position_grade')->references(['id'])->on('position_grade')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['services_type_id'], 'FK_users_services_type')->references(['id'])->on('services_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['position_id'], 'FK_users_position')->references(['id'])->on('position')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['position_type_id'], 'FK_users_position_status')->references(['id'])->on('position_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['marital_status_id'], 'FK_users_marital_status')->references(['id'])->on('marital_status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('FK_users_position_grade');
            $table->dropForeign('FK_users_services_type');
            $table->dropForeign('FK_users_position');
            $table->dropForeign('FK_users_position_status');
            $table->dropForeign('FK_users_marital_status');
        });
    }
}
