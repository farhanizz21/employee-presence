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
            $table->date('tgl_absen');
            $table->tinyInteger('shift');
            $table->tinyInteger('status');
            $table->integer('pencapaian')->default(0);
            $table->boolean('is_lembur')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['pegawai_uuid', 'tgl_absen', 'shift']);
            
            $table->foreign('pegawai_uuid')
                ->references('uuid')
                ->on('pegawais')
                ->cascadeOnDelete();
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