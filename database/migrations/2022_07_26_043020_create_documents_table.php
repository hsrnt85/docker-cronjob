<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->integer('id', true);
            $table->text('document_name')->nullable();
            $table->tinyInteger('data_status')->nullable()->default(1);
            $table->integer('action_by')->nullable()->index('FK_document_users');
            $table->dateTime('action_on')->nullable();
            $table->integer('delete_by')->nullable()->index('FK_document_users_2');
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
        Schema::dropIfExists('documents');
    }
}
