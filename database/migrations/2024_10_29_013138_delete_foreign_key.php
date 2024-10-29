<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['id_roles']);
            $table->dropIndex(['satuan_kerja_id']);
            $table->dropIndex(['balai_kerja_id']);
        });

        Schema::table('kategori_vendors', function (Blueprint $table) {
            $table->dropIndex(['jenis_vendor_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
