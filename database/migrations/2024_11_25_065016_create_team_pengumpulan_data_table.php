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
        Schema::create('team_pengumpulan_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama_team')->nullable();
            $table->string('nama_ketua')->nullable();
            $table->string('nama_sekretaris')->nullable();
            $table->string('nama_anggota')->nullable();
            $table->string('url_sk_penugasan')->nullable();
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
        Schema::dropIfExists('team_pengumpulan_data');
    }
};
