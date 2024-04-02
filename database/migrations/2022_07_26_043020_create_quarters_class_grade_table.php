<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartersClassGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarters_class_grade', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('q_class_id')->nullable()->index('FK_quarters_class_grade_quarters_class');
            $table->integer('p_grade_id')->nullable()->index('FK_quarters_class_grade_position_grade');
            $table->decimal('rental_fee', 10)->nullable();
            $table->integer('officer_type_id')->nullable()->index('FK_quarters_class_grade_officer_type');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_quarters_class_grade_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_quarters_class_grade_users_2');
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
        Schema::dropIfExists('quarters_class_grade');
    }
}
