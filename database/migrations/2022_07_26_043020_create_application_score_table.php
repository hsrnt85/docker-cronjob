<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationScoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_score', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('application_id')->nullable()->index('FK_application_score_application');
            $table->integer('c_category_id')->nullable();
            $table->integer('s_criteria_id')->nullable();
            $table->integer('s_sub_criteria_id')->nullable()->index('FK_application_score_selection_sub_criteria');
            $table->integer('flag')->nullable()->comment('1:yes;0:no');
            $table->integer('mark')->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable();
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable();
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
        Schema::dropIfExists('application_score');
    }
}
