<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('user_id')->nullable()->index('FK_tenants_users_3');
            $table->integer('application_id')->nullable()->index('FK_tenants_application');
            $table->string('name')->nullable();
            $table->string('new_ic', 50)->nullable();
            $table->string('old_ic', 50)->nullable();
            $table->text('organization_name')->nullable();
            $table->integer('position_id')->nullable()->default(0);
            $table->string('position')->nullable();
            $table->integer('position_grade_type_id')->nullable()->default(0);
            $table->string('position_grade_type', 50)->nullable();
            $table->integer('position_grade_id')->nullable()->default(0);
            $table->string('position_grade', 50)->nullable();
            $table->integer('position_type_id')->nullable()->default(0);
            $table->string('position_type')->nullable();
            $table->integer('services_type_id')->nullable()->default(0);
            $table->string('services_type')->nullable();
            $table->integer('marital_status_id')->nullable()->default(0);
            $table->string('marital_status')->nullable();
            $table->string('phone_no_home', 50)->nullable();
            $table->string('phone_no_hp', 50)->nullable();
            $table->string('phone_no_office', 50)->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_tenants_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_tenants_users_2');
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
        Schema::dropIfExists('tenants');
    }
}
