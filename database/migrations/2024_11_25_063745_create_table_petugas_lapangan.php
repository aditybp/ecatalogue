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
        Schema::create('petugas_lapangan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_petugas_lapangan')->nullable();
            $table->string('nip_petugas_lapangan')->nullable();
            $table->date('tanggal_survey')->nullable();
            $table->string('nama_pengawas')->nullable();
            $table->string('nip_pengawas')->nullable();
            $table->date('tanggal_pengawasan')->nullable();
            $table->string('pemberi_informasi')->nullable();
            $table->string('tanda_tangan_responden');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_petugas_lapangan');
    }
};
