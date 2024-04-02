<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSelectionCriteriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('selection_criteria', function (Blueprint $table) {
            $table->foreign(['delete_by'], 'FK_selection_criteria_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_selection_criteria_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['c_category_id'], 'FK_selection_criteria_criteria_category')->references(['id'])->on('criteria_category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('selection_criteria', function (Blueprint $table) {
            $table->dropForeign('FK_selection_criteria_users_2');
            $table->dropForeign('FK_selection_criteria_users');
            $table->dropForeign('FK_selection_criteria_criteria_category');
        });
    }
}
