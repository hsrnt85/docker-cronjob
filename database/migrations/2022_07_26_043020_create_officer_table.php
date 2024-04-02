<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('officer', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->nullable()->index('FK_officer_users');
            $table->integer('district_id')->nullable()->index('FK_officer_district');
            $table->string('officer_category_id', 50)->nullable()->index('FK_officer_officer_category')->comment('eg: 1,2,3');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_officer_users_2');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_officer_users_3');
            $table->dateTime('delete_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('officer');
    }
}
