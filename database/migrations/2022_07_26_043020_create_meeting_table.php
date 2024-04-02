<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('district_id')->nullable()->default(0)->index('FK_meeting_district');
            $table->string('bil_no')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->string('purpose')->nullable();
            $table->string('venue')->nullable();
            $table->string('chairman')->nullable();
            $table->string('letter_ref_no', 50);
            $table->date('letter_date')->nullable();
            $table->integer('is_done')->nullable()->default(0)->comment('1:Meeting Selesai');
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
        Schema::dropIfExists('meeting');
    }
}
