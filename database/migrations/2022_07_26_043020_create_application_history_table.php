<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_history', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('application_id')->nullable()->index('FK_application_history_application');
            $table->integer('application_status_id')->nullable()->index('FK_application_history_application_status');
            $table->integer('data_status')->nullable();
            $table->integer('action_by')->nullable()->index('FK_application_history_users');
            $table->dateTime('action_on')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_history');
    }
}
