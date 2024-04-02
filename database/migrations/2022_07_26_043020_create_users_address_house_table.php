<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAddressHouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_address_house', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->nullable()->index('FK_users_address_house_users');
            $table->text('address_1')->nullable();
            $table->text('address_2')->nullable();
            $table->text('address_3')->nullable();
            $table->string('postcode', 50)->nullable();
            $table->string('phone_no_house', 50)->nullable();
            $table->decimal('latitude', 10, 10)->nullable();
            $table->decimal('longitude', 10, 10)->nullable();
            $table->integer('address_type')->nullable()->comment('1:TETAP;2:SURAT MENYURAT');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_users_address_house_users_2');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_users_address_house_users_3');
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
        Schema::dropIfExists('users_address_house');
    }
}
