<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCriteriaCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('criteria_category', function (Blueprint $table) {
            $table->foreign(['delete_by'], 'FK_criteria_category_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_criteria_category_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('criteria_category', function (Blueprint $table) {
            $table->dropForeign('FK_criteria_category_users_2');
            $table->dropForeign('FK_criteria_category_users');
        });
    }
}
