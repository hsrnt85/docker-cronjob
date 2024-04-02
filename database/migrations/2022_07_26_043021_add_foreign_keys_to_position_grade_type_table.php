<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToPositionGradeTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('position_grade_type', function (Blueprint $table) {
            $table->foreign(['delete_by'], 'FK_position_grade_type_users_2')->references(['id'])->on('users');
            $table->foreign(['action_by'], 'FK_position_grade_type_users')->references(['id'])->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('position_grade_type', function (Blueprint $table) {
            $table->dropForeign('FK_position_grade_type_users_2');
            $table->dropForeign('FK_position_grade_type_users');
        });
    }
}
