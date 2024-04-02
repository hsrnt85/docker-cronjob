<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application', function (Blueprint $table) {
            $table->foreign(['q_category_id'], 'FK_application_quarters_category')->references(['id'])->on('quarters_category');
            $table->foreign(['action_by'], 'FK_application_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['q_id'], 'FK_application_quarters')->references(['id'])->on('quarters');
            $table->foreign(['user_id'], 'FK_application_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_application_users_3')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['meeting_id'], 'FK_application_meeting')->references(['id'])->on('meeting')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application', function (Blueprint $table) {
            $table->dropForeign('FK_application_quarters_category');
            $table->dropForeign('FK_application_users_2');
            $table->dropForeign('FK_application_quarters');
            $table->dropForeign('FK_application_users');
            $table->dropForeign('FK_application_users_3');
            $table->dropForeign('FK_application_meeting');
        });
    }
}
