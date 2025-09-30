<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gajian_details', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->uuid('uuid');
        $table->uuid('gajian_uuid');
        $table->date('tanggal');
        $table->enum('jenis', ['gaji_harian','lembur','potongan','bonus']);
        $table->integer('nominal');
        $table->text('keterangan')->nullable();
        $table->string('tipe')->nullable(); // opsional, untuk trace
        $table->uuid('source_uuid')->nullable();   // opsional, untuk trace
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajian_details');
    }
};
