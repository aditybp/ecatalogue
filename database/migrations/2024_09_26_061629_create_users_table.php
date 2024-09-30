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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_roles');
            $table->string('nama_lengkap');
            $table->integer('no_handphone');
            $table->integer('nik');
            $table->integer('nip');
            $table->unsignedBigInteger('satuan_kerja_id');
            $table->unsignedBigInteger('balai_kerja_id');
            $table->enum('status', ['active', 'register', 'expired']);
            $table->timestamp('created_at')->nullable(); 
            $table->timestamp('edited_at')->nullable(); 
            $table->timestamp('deleted_at')->nullable();

            //foreign key
            // $table->foreign('id_roles')->references('id')->on('roles');
            // $table->foreign('satuan_kerja_id')->references('id')->on('satuan_kerja');
            // $table->foreign('balai_kerja_id')->references('id')->on('balai_kerja');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
