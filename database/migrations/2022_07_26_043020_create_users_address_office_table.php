<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAddressOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_address_office', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->nullable()->index('FK_users_address_office_users');
            $table->integer('organization_id')->nullable();
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('address_3')->nullable();
            $table->integer('district_id')->nullable()->index('FK_users_address_office_district');
            $table->string('postcode', 50)->nullable();
            $table->string('phone_no_office', 50)->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_users_address_office_users_2');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_users_address_office_users_3');
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
        Schema::dropIfExists('users_address_office');
    }
}
