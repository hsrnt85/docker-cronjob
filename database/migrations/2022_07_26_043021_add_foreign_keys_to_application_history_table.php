<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_history', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_application_history_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['application_status_id'], 'FK_application_history_application_status')->references(['id'])->on('application_status')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['application_id'], 'FK_application_history_application')->references(['id'])->on('application')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_history', function (Blueprint $table) {
            $table->dropForeign('FK_application_history_users');
            $table->dropForeign('FK_application_history_application_status');
            $table->dropForeign('FK_application_history_application');
        });
    }
}
