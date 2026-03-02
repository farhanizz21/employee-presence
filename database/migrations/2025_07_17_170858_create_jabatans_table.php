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
        Schema::create('jabatans', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->Integer('gaji_pagi');
            $table->Integer('gaji_malam');
            $table->integer('harian'); //keterangan 1=gajian / 2=borongan
            $table->string('jabatan', 100);
            $table->uuid('bonus_uuid')->nullable(); //kondisional per jabatan
            $table->text('keterangan')->nullable(); // Deskripsi atau keterangan
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
        Schema::dropIfExists('jabatans');
    }
};