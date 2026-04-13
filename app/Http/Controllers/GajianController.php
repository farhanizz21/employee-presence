<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

use App\Models\Gajian;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\BonusPotongan;
use App\Models\Absensi;
use App\Models\GajianPeriode;

class GajianController extends Controller
{
     public function index()
    {
        $periodes = GajianPeriode::orderBy('created_at', 'desc')->get();

        return view('gajian.index', compact('periodes'));
    }

    public function create()
    {
        return view('gajian.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        GajianPeriode::create([
            'uuid' => Str::uuid(),
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => 'draft',
        ]);

        return redirect()->route('gajian.index')
            ->with('success', 'Periode berhasil dibuat (Draft)');
    }

    public function proses($uuid)
    {
        DB::beginTransaction();

        try {
            $periode = GajianPeriode::where('uuid', $uuid)->firstOrFail();

            // ❗ Cegah double proses
            if ($periode->status !== 'draft') {
                return redirect()->back()->with('error', 'Periode sudah diproses!');
            }

            $pegawais = Pegawai::with('jabatan')->get();

            foreach ($pegawais as $pegawai) {

                // 🔹 Ambil absensi sesuai periode
                $absensis = Absensi::where('pegawai_uuid', $pegawai->uuid)
                    ->whereBetween('tgl_absen', [$periode->tanggal_mulai, $periode->tanggal_selesai])
                    ->get();

                $hadir = $absensis->where('status', '1')->count();
                $izin  = $absensis->where('status', '2')->count();
                $alpha = $absensis->where('status', '3')->count();

                $gajiPokok = 0;

                foreach ($absensis as $absen) {

                    // hanya hitung yang hadir
                    if ($absen->status != '1') {
                        continue;
                    }

                    // tentukan tarif berdasarkan shift
                    if ($absen->shift == '1') {
                        $tarif = $pegawai->jabatan->gaji_pagi ?? 0;
                    } elseif ($absen->shift == '2') {
                        $tarif = $pegawai->jabatan->gaji_malam ?? 0;
                    } else {
                        $tarif = 0;
                    }

                    $gajiPokok += $tarif;
                }

                // HITUNGAN POTONGAN ALPHA
                $potongan = 0;

                //HITUNGAN BONUS KEHADIRAN
                $bonus = 0;

                $hutang = DB::table('hutangs')
                    ->where('pegawai_uuid', $pegawai->uuid)
                    ->where('is_active', 1)
                    ->sum('nominal');

                $potongan += $hutang;
                $gajiBersih = $gajiPokok - $potongan;

                // dd($pegawai->nama, $tarif, $hadir, $izin, $alpha, $gajiPokok, $potongan, $gajiBersih);
// dd($absensis->pluck('status'));
                Gajian::create([
                    'uuid' => Str::uuid(),
                    'periode_uuid' => $periode->uuid,
                    'pegawai_uuid' => $pegawai->uuid,

                    'hadir' => $hadir,
                    'izin' => $izin,
                    'alpha' => $alpha,

                    'gaji_pokok' => $gajiPokok,
                    'bonus' => $bonus,
                    'potongan' => $potongan,
                    
                    'gaji_bersih' => $gajiBersih,
                ]);

            }

            // 🔥 Ubah status periode
            $periode->update([
                'status' => 'calculated'
            ]);

            DB::commit();

            return redirect()->route('gajian.index')
                ->with('success', 'Gajian berhasil diproses');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function show($uuid)
    {
        $periode = GajianPeriode::where('uuid', $uuid)->firstOrFail();

        $gajians = Gajian::with('pegawai')
            ->where('periode_uuid', $uuid)
            ->get();

        return view('gajian.show', compact('periode', 'gajians'));
    }

    public function final($uuid)
    {
        $periode = GajianPeriode::where('uuid', $uuid)->firstOrFail();

        if ($periode->status !== 'calculated') {
            return back()->with('error', 'Belum bisa difinalisasi');
        }

        $periode->update([
            'status' => 'final'
        ]);

        return redirect()->route('gajian.index')
            ->with('success', 'Gajian berhasil difinalisasi & dikunci');
    }
}