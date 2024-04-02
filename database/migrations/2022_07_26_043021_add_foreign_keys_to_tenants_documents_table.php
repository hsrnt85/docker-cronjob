<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTenantsDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants_documents', function (Blueprint $table) {
            $table->foreign(['delete_by'], 'FK_tenants_documents_users_2')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['action_by'], 'FK_tenants_documents_users')->references(['id'])->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign(['t_id'], 'FK_tenants_documents_tenants')->references(['id'])->on('tenants')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants_documents', function (Blueprint $table) {
            $table->dropForeign('FK_tenants_documents_users_2');
            $table->dropForeign('FK_tenants_documents_users');
            $table->dropForeign('FK_tenants_documents_tenants');
        });
    }
}
