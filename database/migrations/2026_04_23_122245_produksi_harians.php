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
        Schema::create('produksi_harians', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->date('tanggal');
            $table->integer('shift')->nullable();;
            $table->uuid('grup_uuid')->nullable();

            $table->integer('mesin_status')->default(0); // 0 normal, 1 rusak
            $table->integer('total_produksi')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produksi_harians');
    }
};