<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('name')->nullable();
            $table->string('new_ic', 12)->nullable();
            $table->string('old_ic', 12)->nullable();
            $table->integer('position_id')->nullable()->index('FK_users_position');
            $table->integer('position_type_id')->nullable()->index('FK_m_pengguna_sistem_x_taraf_jawatan');
            $table->integer('position_grade_id')->nullable()->index('FK_users_position_grade');
            $table->integer('services_type_id')->nullable()->index('FK_m_pengguna_sistem_x_taraf_perkhidmatan');
            $table->integer('marital_status_id')->nullable()->index('FK_m_pengguna_sistem_x_taraf_perkahwinan');
            $table->integer('roles_id')->nullable()->default(0)->index('FK_users_users_policy');
            $table->string('password')->nullable()->comment('Min 12 Char; A-Z,a-z,@!_-@#%*,0-9');
            $table->string('email', 50)->nullable();
            $table->string('phone_no_hp', 50)->nullable();
            $table->integer('is_blacklist')->default(1);
            $table->date('blacklist_date')->nullable();
            $table->longText('blacklist_reason')->nullable();
            $table->integer('is_blacklist_application')->default(1);
            $table->date('blacklist_date_application')->nullable();
            $table->longText('blacklist_reason_application')->nullable();
            $table->tinyInteger('is_hrmis')->nullable()->default(0)->comment('0:NORMAL; 1:HRMIS');
            $table->integer('flag')->default(1)->comment('1:Sistem Admin&Portal, 2:Portal Only');
            $table->integer('data_status')->default(1)->comment('0:Hapus,1: Aktif, 2:Upon Approval');
            $table->string('action_by', 50)->nullable();
            $table->string('action_on', 50)->nullable();
            $table->string('deleted_by', 50)->nullable();
            $table->string('deleted_on', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
