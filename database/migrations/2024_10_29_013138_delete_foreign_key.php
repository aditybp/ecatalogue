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
            $table->dropForeign(['id_roles']);
            $table->dropForeign(['satuan_kerja_id']);
            $table->dropForeign(['balai_kerja_id']);
        });

        // Schema::table('kategori_vendors', function (Blueprint $table) {
        //     $table->dropForeign(['jenis_vendor_id']);
        // });
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
