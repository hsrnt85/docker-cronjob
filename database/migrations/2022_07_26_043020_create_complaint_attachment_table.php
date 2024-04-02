<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_attachment', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('complaint_id')->nullable();
            $table->text('path_document')->nullable();
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
        Schema::dropIfExists('complaint_attachment');
    }
}
