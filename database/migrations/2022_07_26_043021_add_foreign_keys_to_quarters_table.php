<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToQuartersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_quarters_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['quarters_cat_id'], 'FK_quarters_quarters_category')->references(['id'])->on('quarters_category')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_quarters_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['m_utility_id'], 'FK_quarters_maintenance_utility')->references(['id'])->on('maintenance_utility')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters', function (Blueprint $table) {
            $table->dropForeign('FK_quarters_users');
            $table->dropForeign('FK_quarters_quarters_category');
            $table->dropForeign('FK_quarters_users_2');
            $table->dropForeign('FK_quarters_maintenance_utility');
        });
    }
}
