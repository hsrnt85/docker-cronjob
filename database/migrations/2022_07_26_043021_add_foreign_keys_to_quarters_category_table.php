<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToQuartersCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quarters_category', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_quarters_category_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['landed_type_id'], 'FK_quarters_category_landed_type')->references(['id'])->on('landed_type');
            $table->foreign(['delete_by'], 'FK_quarters_category_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['district_id'], 'FK_quarters_category_district')->references(['id'])->on('district')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quarters_category', function (Blueprint $table) {
            $table->dropForeign('FK_quarters_category_users');
            $table->dropForeign('FK_quarters_category_landed_type');
            $table->dropForeign('FK_quarters_category_users_2');
            $table->dropForeign('FK_quarters_category_district');
        });
    }
}
