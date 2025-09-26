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
        Schema::create('gajians', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('pegawai_uuid');
            $table->uuid('jabatan_uuid');
            $table->integer('gaji_pokok');
            $table->integer('bonus_kehadiran');
            $table->integer('bonus_lembur');
            $table->integer('total_potongan');
            $table->integer('total_gaji');
            $table->integer('jumlah_hadir');  
            $table->integer('jumlah_lembur');
            $table->integer('jumlah_telat');
            $table->integer('jumlah_alpha');
            $table->text('keterangan')->nullable();
            $table->date('periode_mulai')->nullable();
            $table->date('periode_selesai')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gajians');
    }
};