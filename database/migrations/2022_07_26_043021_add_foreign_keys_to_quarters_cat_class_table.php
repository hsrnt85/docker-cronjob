<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToQuartersCatClassTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters_cat_class', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_quarters_cat_class_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['q_class_id'], 'FK_quarters_cat_class_quarters_class')->references(['id'])->on('quarters_class')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_quarters_cat_class_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['q_cat_id'], 'FK_quarters_cat_class_quarters_category')->references(['id'])->on('quarters_category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters_cat_class', function (Blueprint $table) {
            $table->dropForeign('FK_quarters_cat_class_users');
            $table->dropForeign('FK_quarters_cat_class_quarters_class');
            $table->dropForeign('FK_quarters_cat_class_users_2');
            $table->dropForeign('FK_quarters_cat_class_quarters_category');
        });
    }
}
