<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistrictManagementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_management', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('district_id')->nullable()->index('FK_district_management_district');
            $table->integer('users_id')->nullable()->index('FK_district_management_users');
            $table->longText('address_1')->nullable();
            $table->longText('address_2')->nullable();
            $table->longText('address_3')->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_district_management_users_2');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_district_management_users_3');
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
        Schema::dropIfExists('district_management');
    }
}
