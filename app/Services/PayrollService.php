<?php

namespace App\Services;

use App\Models\Pegawai;
use App\Models\Absensi;
use App\Models\Gajian;
use App\Models\GajianDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PayrollService
{
    public function generate(string $pegawaiUuid, string $periodeMulai, string $periodeSelesai): Gajian
    {
        $pegawai = Pegawai::where('uuid', $pegawaiUuid)->firstOrFail();

        // Pastikan belum ada gaji untuk periode ini
        $existing = Gajian::where('pegawai_uuid', $pegawaiUuid)
            ->where('periode_mulai', $periodeMulai)
            ->where('periode_selesai', $periodeSelesai)
            ->first();

        if ($existing) {
            return $existing;
        }

        return DB::transaction(function () use ($pegawai, $pegawaiUuid, $periodeMulai, $periodeSelesai) {
            // ambil absensi dalam periode
            $absensis = Absensi::where('pegawai_uuid', $pegawaiUuid)
                ->whereBetween('tgl_absen', [$periodeMulai, $periodeSelesai])
                ->get();

            $totalHadir = $absensis->where('status', 1)->count();
            $totalLembur = $absensis->where('status', 2)->count();
            $totalTelat = $absensis->where('status', 3)->count();
            $totalAlpha = $absensis->where('status', 4)->count();

            // hitung gaji dasar
            $gajiHarian = $pegawai->jabatan->gaji ?? 0;
            $gajiPokok = $gajiHarian * $totalHadir;
            $bonusLembur = 50000 * $totalLembur; // aturan contoh
            $potonganTelat = 20000 * $totalTelat;
            $potonganAlpha = 50000 * $totalAlpha;

            // contoh aturan: bonus kehadiran jika tanpa telat & alpha
            $bonusKehadiran = ($totalTelat === 0 && $totalAlpha === 0) ? 100000 : 0;

            $totalGaji = $gajiPokok + $bonusLembur + $bonusKehadiran
                - ($potonganTelat + $potonganAlpha);

            // buat entry gajian
            $gajian = Gajian::create([
                'uuid' => Str::uuid(),
                'pegawai_uuid' => $pegawaiUuid,
                'jabatan_uuid' => $pegawai->jabatan_uuid,
                'gaji_pokok' => $gajiPokok,
                'bonus_kehadiran' => $bonusKehadiran,
                'bonus_lembur' => $bonusLembur,
                'total_potongan' => $potonganTelat + $potonganAlpha,
                'total_gaji' => $totalGaji,
                'jumlah_hadir' => $totalHadir,
                'jumlah_lembur' => $totalLembur,
                'jumlah_telat' => $totalTelat,
                'jumlah_alpha' => $totalAlpha,
                'periode_mulai' => $periodeMulai,
                'periode_selesai' => $periodeSelesai,
                'keterangan' => "Gaji periode {$periodeMulai} - {$periodeSelesai}",
            ]);

            // detail gaji per hari
            foreach ($absensis as $absen) {
                GajianDetail::create([
                    'gajian_uuid' => $gajian->uuid,
                    'tanggal' => $absen->tgl_absen,
                    'jenis' => $this->mapStatus($absen->status),
                    'nominal' => $this->calcNominal($pegawai, $absen),
                    'keterangan' => '',
                ]);
            }

            return $gajian;
        });
    }

    private function mapStatus(int $status): string
    {
        return match ($status) {
            1 => 'gaji_harian',
            2 => 'lembur',
            3, 4 => 'potongan',
            default => 'gaji_harian',
        };
    }

    private function calcNominal(Pegawai $pegawai, Absensi $absen): int
    {
        $gajiHarian = $pegawai->jabatan->gaji ?? 0;

        return match ($absen->status) {
            1 => $gajiHarian,  // hadir
            2 => 50000,        // lembur
            3 => -20000,       // telat
            4 => -50000,       // alpa
            default => 0,
        };
    }
}
