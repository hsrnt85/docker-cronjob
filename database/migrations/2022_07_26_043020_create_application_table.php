<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('application_type')->nullable()->comment('1:NORMAL; 2:GREENLANE');
            $table->integer('user_id')->nullable()->index('FK_application_users');
            $table->integer('application_status')->nullable()->comment('refer tbl application_status');
            $table->integer('q_category_id')->nullable()->index('FK_application_quarters_category');
            $table->integer('q_id')->nullable()->index('FK_application_quarters');
            $table->integer('meeting_id')->nullable()->index('FK_application_meeting');
            $table->integer('is_rental')->nullable()->default(0)->comment('1:YA');
            $table->decimal('rental_fee', 10)->nullable();
            $table->date('duration_year')->nullable();
            $table->date('duration_month')->nullable();
            $table->date('duration_day')->nullable();
            $table->string('landlord_name')->nullable();
            $table->text('landlord_address_1')->nullable();
            $table->text('landlord_address_2')->nullable();
            $table->text('landlord_address_3')->nullable();
            $table->string('landlord_postcode', 50)->nullable();
            $table->string('landlord_phone', 50)->nullable();
            $table->tinyInteger('is_draft')->nullable()->default(1)->comment('0:submit;1:draft');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_application_users_2');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_application_users_3');
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
        Schema::dropIfExists('application');
    }
}
