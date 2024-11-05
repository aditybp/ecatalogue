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
        Schema::create('kuisioner_pdf_data', function (Blueprint $table) {
            $table->id();
            $table->string('material');
            $table->string('peralatan');
            $table->string('tenaga_kerja');
            $table->unsignedBigInteger('shortlist_id');
            $table->unsignedBigInteger('vendor_id');
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
        Schema::dropIfExists('kuisioner_pdf_data');
    }
};
