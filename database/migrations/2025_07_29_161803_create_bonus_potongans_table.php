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
        Schema::create('bonus_potongans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('nama', 100);
            $table->string('kode')->nullable()->unique();
            $table->unsignedTinyInteger('jenis'); // 1=Bonus, 2=Potongan
            $table->integer('nominal'); // Jumlah nominal
            $table->text('keterangan')->nullable(); // Deskripsi atau keterangan
            $table->unsignedTinyInteger('status')->default(1); // 1=Aktif, 2=Nonaktif
            $table->json('jabatan')->nullable(); // UUID Jabatan
            $table->boolean('is_system')->default(false); // True untuk data bawaan sistem
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bonus_potongans');
    }
};