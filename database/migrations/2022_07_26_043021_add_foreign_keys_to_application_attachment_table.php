<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToApplicationAttachmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_attachment', function (Blueprint $table) {
            $table->foreign(['action_by'], 'FK_application_attachment_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['d_id'], 'FK_application_attachment_documents')->references(['id'])->on('documents')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['delete_by'], 'FK_application_attachment_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['a_id'], 'FK_application_attachment_application')->references(['id'])->on('application')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('application_attachment', function (Blueprint $table) {
            $table->dropForeign('FK_application_attachment_users');
            $table->dropForeign('FK_application_attachment_documents');
            $table->dropForeign('FK_application_attachment_users_2');
            $table->dropForeign('FK_application_attachment_application');
        });
    }
}
