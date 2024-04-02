<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicationScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_score', function (Blueprint $table) {
            $table->foreign(['s_sub_criteria_id'], 'FK_application_score_selection_sub_criteria')->references(['id'])->on('selection_sub_criteria')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['application_id'], 'FK_application_score_application')->references(['id'])->on('application')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_score', function (Blueprint $table) {
            $table->dropForeign('FK_application_score_selection_sub_criteria');
            $table->dropForeign('FK_application_score_application');
        });
    }
}
