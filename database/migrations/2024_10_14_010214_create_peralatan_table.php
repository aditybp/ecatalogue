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
        Schema::create('peralatan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('perencanaan_data_id');
            $table->string('nama_peralatan');
            $table->string('satuan');
            $table->string('spesifikasi');
            $table->string('kapasitas');
            $table->string('kodefikasi');
            $table->string('kelompok_peralatan');
            $table->string('jumlah_kebutuhan');
            $table->string('merk');
            $table->unsignedBigInteger('provincies_id');
            $table->unsignedBigInteger('cities_id');
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
        Schema::dropIfExists('peralatan');
    }
};
