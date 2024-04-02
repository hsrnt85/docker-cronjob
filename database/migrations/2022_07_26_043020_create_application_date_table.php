<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationDateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_date', function (Blueprint $table) {
            $table->integer('id', true);
            $table->year('year')->nullable();
            $table->date('date_open')->nullable();
            $table->date('date_close')->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_application_date_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_application_date_users_2');
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
        Schema::dropIfExists('application_date');
    }
}
