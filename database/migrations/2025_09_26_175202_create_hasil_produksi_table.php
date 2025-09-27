<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_produksi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique(); // satu tanggal hanya 1 data
            $table->integer('hasil_pagi')->default(0);
            $table->integer('hasil_malam')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_produksi');
    }
};
