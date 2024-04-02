<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSelectionSubCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selection_sub_criteria', function (Blueprint $table) {
            $table->foreign(['delete_by'], 'FK_selection_sub_criteria_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_selection_sub_criteria_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['s_criteria_id'], 'FK_selection_sub_criteria_selection_criteria')->references(['id'])->on('selection_criteria')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selection_sub_criteria', function (Blueprint $table) {
            $table->dropForeign('FK_selection_sub_criteria_users_2');
            $table->dropForeign('FK_selection_sub_criteria_users');
            $table->dropForeign('FK_selection_sub_criteria_selection_criteria');
        });
    }
}
