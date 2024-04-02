<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->nullable();
            $table->integer('complaint_date')->nullable();
            $table->integer('quarters_id')->nullable();
            $table->string('action_by', 50)->nullable();
            $table->string('action_on', 50)->nullable();
            $table->string('delete_by', 50)->nullable();
            $table->string('delete_on', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint');
    }
}
