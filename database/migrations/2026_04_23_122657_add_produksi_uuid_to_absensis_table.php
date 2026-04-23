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
        Schema::table('absensis', function (Blueprint $table) {
            $table->uuid('produksi_uuid')
                ->nullable()
                ->after('pegawai_uuid');
                $table->uuid('jabatan_uuid')->nullable()->after('produksi_uuid');
                $table->uuid('grup_uuid')->nullable()->after('jabatan_uuid');

            $table->foreign('produksi_uuid')
                ->references('uuid')
                ->on('produksi_harians')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['produksi_uuid']);
            $table->dropColumn('produksi_uuid');
            $table->dropColumn('produksi_uuid');
             $table->dropColumn('jabatan_uuid');
             $table->dropColumn('grup_uuid');
        });
    }
};