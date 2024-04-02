<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingPanelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting_panel', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('m_id')->nullable()->index('FK_meeting_user_meeting');
            $table->integer('users_id')->nullable()->index('FK_meeting_user_users');
            $table->integer('is_attend')->nullable()->default(0)->comment('1:attend meeting');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable();
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable();
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
        Schema::dropIfExists('meeting_panel');
    }
}
