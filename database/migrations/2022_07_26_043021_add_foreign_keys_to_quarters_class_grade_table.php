<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToQuartersClassGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters_class_grade', function (Blueprint $table) {
            $table->foreign(['q_class_id'], 'FK_quarters_class_grade_quarters_class')->references(['id'])->on('quarters_class')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_quarters_class_grade_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['p_grade_id'], 'FK_quarters_class_grade_position_grade')->references(['id'])->on('position_grade')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_quarters_class_grade_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['officer_type_id'], 'FK_quarters_class_grade_officer_type')->references(['id'])->on('officer_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters_class_grade', function (Blueprint $table) {
            $table->dropForeign('FK_quarters_class_grade_quarters_class');
            $table->dropForeign('FK_quarters_class_grade_users_2');
            $table->dropForeign('FK_quarters_class_grade_position_grade');
            $table->dropForeign('FK_quarters_class_grade_users');
            $table->dropForeign('FK_quarters_class_grade_officer_type');
        });
    }
}
