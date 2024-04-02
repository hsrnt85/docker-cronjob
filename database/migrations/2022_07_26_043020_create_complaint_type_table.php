<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_type', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('complaint_name')->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
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
        Schema::dropIfExists('complaint_type');
    }
}
