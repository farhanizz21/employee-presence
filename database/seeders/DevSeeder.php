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