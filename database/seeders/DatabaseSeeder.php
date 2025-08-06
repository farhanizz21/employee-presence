<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Master\User;
use App\Models\Master\BonusPotongan;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        User::updateOrCreate(
                ['email' => 'admin@example.com'],
                [
                'uuid' => Str::uuid(), // Generate UUID otomatis
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => bcrypt('name'),
                'pegawai_uuid' => null, // Admin tidak terkait dengan pegawai
                'role' => '1', // 1= Admin, 2=User
                ]
            );
        BonusPotongan::updateOrCreate(
            [
                'nama' => 'Bonus Kehadiran',
                'kode' => 'bonus_kehadiran',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 100000,
                'keterangan' => 'Bonus untuk pegawai hadir penuh',
                'status' => 1,
                'is_system' => true
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'nama' => 'Bonus Lembur',
                'kode' => 'bonus_lembur',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 100000,
                'keterangan' => 'Bonus lembur',
                'status' => 1,
                'is_system' => true
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'nama' => 'Potongan Terlambat',
                'kode' => 'potongan_terlambat',
                'uuid' => Str::uuid(),
                'jenis' => 2,
                'nominal' => 50000,
                'keterangan' => '-',
                'status' => 1,
                'is_system' => true
            ]
        );
    }
}