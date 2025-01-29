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
        Schema::table('shortlist_vendor', function (Blueprint $table) {
            $table->string('file_kuisioner')->nullable();
            $table->string('catatan')->nullable();
        });

        Schema::table('keterangan_petugas_survey', function (Blueprint $table) {
            $table->string('catatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shortlist_vendor', function (Blueprint $table) {
            //
        });
    }
};
