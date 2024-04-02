<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services_type', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('services_type', 50)->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_services_category_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_services_category_users_2');
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
        Schema::dropIfExists('services_type');
    }
}
