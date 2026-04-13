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
            $table->uuid('periode_uuid');
            $table->uuid('pegawai_uuid');

            $table->integer('hadir')->default(0);
            $table->integer('izin')->default(0);
            $table->integer('alpha')->default(0);

            $table->decimal('gaji_pokok', 12, 2)->default(0);
            $table->decimal('bonus', 12, 2)->default(0);
            $table->decimal('potongan', 12, 2)->default(0);

            $table->decimal('gaji_bersih', 12, 2)->default(0);

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