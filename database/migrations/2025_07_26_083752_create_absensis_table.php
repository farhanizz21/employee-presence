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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->uuid('pegawai_uuid');
            $table->uuid('periode_uuid');
            $table->uuid('grup_uuid'); // Optional foreign key to Grup
            $table->uuid('jabatan_uuid');
            $table->uuid('grup_sb');
            $table->string('status', 20);
            $table->integer('pencapaian')->nullable();
            $table->date('tgl_absen'); // Date of attendance
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};