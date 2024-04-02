<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMeetingPanelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meeting_panel', function (Blueprint $table) {
            $table->foreign(['users_id'], 'FK_meeting_user_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['m_id'], 'FK_meeting_user_meeting')->references(['id'])->on('meeting')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meeting_panel', function (Blueprint $table) {
            $table->dropForeign('FK_meeting_user_users');
            $table->dropForeign('FK_meeting_user_meeting');
        });
    }
}
