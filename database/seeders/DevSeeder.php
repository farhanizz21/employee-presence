<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Master\User;
use App\Models\Master\BonusPotongan;
use App\Models\Master\Jabatan;
use App\Models\Master\Grup;
use App\Models\Master\Pegawai;

class DevSeeder extends Seeder
{
    
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
            'uuid' => Str::uuid(), // Generate UUID otomatis
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'pegawai_uuid' => null, // Admin tidak terkait dengan pegawai
            'role' => '1', // 1= Admin, 2=User
            ]
        );
        Jabatan::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'gaji_pagi' => 100,
                'gaji_malam' => 105,
                'harian' => 2,
                'jabatan' => 'Nge Ces',
                'bonus_uuid' => NULL,
                'keterangan' => 'Perhitungan : ',
                'is_system' => true
            ]
        );
        Jabatan::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'gaji_pagi' => 110,
                'gaji_malam' => 115,
                'harian' => 2,
                'jabatan' => 'Nge Pan',
                'bonus_uuid' => NULL,
                'keterangan' => 'Perhitungan : ',
                'is_system' => true
            ]
        );

        Jabatan::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'gaji_pagi' => 4500,
                'gaji_malam' => 5000,
                'harian' => 1,
                'jabatan' => 'Kantor',
                'bonus_uuid' => NULL,
                'keterangan' => 'Perhitungan : ',
                'is_system' => false
            ]
        );
        Jabatan::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'gaji_pagi' => 110,
                'gaji_malam' => 115,
                'harian' => 2,
                'jabatan' => 'Tukang',
                'bonus_uuid' => NULL,
                'keterangan' => 'Perhitungan : ',
                'is_system' => false
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'nama' => 'Bonus Kehadiran A',
                'kode' => 'bonus_kehadiran',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 10000,
                'keterangan' => 'Bonus untuk pegawai hadir penuh',
                'status' => 1,
                'is_system' => true
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'nama' => 'Bonus Kehadiran B',
                'kode' => 'bonus_kehadiran_b',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 15000,
                'keterangan' => 'Bonus untuk pegawai hadir penuh',
                'status' => 1,
                'is_system' => true
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'nama' => 'Potongan Alpha',
                'kode' => 'potongan_alpha',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 2500,
                'keterangan' => 'Potongan Alpha',
                'status' => 1,
                'is_system' => true
            ]
        );
        // BonusPotongan::updateOrCreate(
        //     [
        //         'nama' => 'Potongan Terlambat',
        //         'kode' => 'potongan_terlambat',
        //         'uuid' => Str::uuid(),
        //         'jenis' => 2,
        //         'nominal' => 50000,
        //         'keterangan' => '-',
        //         'status' => 1,
        //         'is_system' => true
        //     ]
        // );
        Grup::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'nama' => 'A',
            ]
        );Grup::updateOrCreate(
            [
                'uuid' => Str::uuid(),
                'nama' => 'B',
            ]
        );
        
        $grups = Grup::all();
        $jabatans = Jabatan::all();
        for ($i = 1; $i <= 20; $i++) {

            $grup = $grups->random();
            $jabatan = $jabatans->random();

            Pegawai::updateOrCreate(
                ['nama' => 'Pegawai ' . $i],
                [
                    'uuid' => Str::uuid(),
                    'telepon' => '08123' . rand(100000, 999999),
                    'grup_uuid' => $grup->uuid,
                    'shift' => rand(1,2),
                    'jabatan_uuid' => $jabatan->uuid,
                    'status' => true,
                    'keterangan' => '-'
                ]
            );
        }
    }
}