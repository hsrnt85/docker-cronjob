<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuartersCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quarters_category', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('district_id')->nullable()->index('FK_quarters_category_district');
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->integer('landed_type_id')->nullable()->index('FK_quarters_category_landed_type');
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_quarters_category_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_quarters_category_users_2');
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
        Schema::dropIfExists('quarters_category');
    }
}
