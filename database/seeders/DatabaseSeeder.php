<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Master\User;
use App\Models\Master\BonusPotongan;
use App\Models\Master\Jabatan;

class DatabaseSeeder extends Seeder
{
    
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@example.com',
            'username' => 'admin',
             'role' => '1', // 1= Admin, 2=User
            ],
            [
            'uuid' => Str::uuid(), // Generate UUID otomatis
            'email' => 'admin@example.com',
            'password' => bcrypt('admin'),
            'pegawai_uuid' => null, // Admin tidak terkait dengan pegawai
            ]
        );
        Jabatan::updateOrCreate(
            [
                'jabatan' => 'Ngepon',
                'is_system' => true
            ],
            [
                'uuid' => Str::uuid(),
                'gaji_pagi' => 110,
                'gaji_malam' => 115,
                'gaji_pokok' => 85000,
                'harian' => 2,
                'bonus_uuid' => NULL,
                'keterangan' => 'Perhitungan : Total produksi per shift dikalikan tarif per kg sesuai shift (pagi/malam),
                 kemudian dibagi rata kepada seluruh karyawan Ngepon pada shift tersebut. Jika hasil perhitungan lebih 
                 rendah dari gaji pokok, maka pegawai tetap mendapatkan gaji pokok.',
            ]
        );
        Jabatan::updateOrCreate(
            [
                'jabatan' => 'Jeladren',
                'is_system' => true
            ],
            [
                'uuid' => Str::uuid(),
                'gaji_pagi' => 160,
                'gaji_malam' => 170,
                'gaji_pokok' => 90000,
                'harian' => 2,
                'bonus_uuid' => NULL,
                'keterangan' => 'Perhitungan : Total produksi per pegawai (kg) dikalikan tarif per kg sesuai shift (pagi/malam). 
                Jika hasil perhitungan lebih rendah dari gaji pokok, maka pegawai tetap mendapatkan gaji pokok.',
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'kode' => 'bonus_kehadiran_a',
                'is_system' => true
            ],
            [
                'nama' => 'Bonus Kehadiran A',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 10000,
                'keterangan' => 'Bonus untuk pegawai hadir penuh',
                'status' => 1
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'kode' => 'bonus_kehadiran_b',
                'is_system' => true
            ],
            [
                'nama' => 'Bonus Kehadiran B',
                'uuid' => Str::uuid(),
                'jenis' => 1,
                'nominal' => 15000,
                'keterangan' => 'Bonus untuk pegawai hadir penuh',
                'status' => 1
            ]
        );
        BonusPotongan::updateOrCreate(
            [
                'kode' => 'potongan_alpha',
                'is_system' => true
            ],
            [
                'nama' => 'Potongan Alpha',
                'uuid' => Str::uuid(),
                'jenis' => 2,
                'nominal' => 2500,
                'keterangan' => 'Potongan Alpha',
                'status' => 1,
            ]
        );
    }
}