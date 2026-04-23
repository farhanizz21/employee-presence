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
use App\Models\Master\Grup;

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

    private function generateGaji($periode)
    {
        $pegawais = Pegawai::with('jabatan')->get();
        $allBonus = BonusPotongan::where('jenis', 1)->get();
        $allPotongan = BonusPotongan::where('jenis', 2)->get();

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

            //POTONGAN
            //PER PEGAWAI SESUAI JABATAN
            $potonganData = $allPotongan->filter(function ($item) use ($pegawai) {
                return in_array($pegawai->jabatan_uuid, $item->jabatan ?? []);
            });
            
            //ATURAN : Alpha = Potongan -2.5rb all jabatan
            $potongan = 0;
            if ($alpha > 0) {
                $potongan = $alpha * $potonganData->sum('nominal');
            }

            //BONUS 
            //PER PEGAWAI SESUAI JABATAN
            $bonusData = $allBonus->filter(function ($item) use ($pegawai) {
                return in_array($pegawai->jabatan_uuid, $item->jabatan ?? []);
            });
             
            //ATURAN : jika ada alpha & izin, maka tidak dapat bonus kehadiran
            $bonus = ($izin == 0 && $alpha == 0 && $hadir > 0)
                ? $bonusData->sum('nominal')
                : 0;

            $hutang = DB::table('hutangs')
                    ->where('pegawai_uuid', $pegawai->uuid)
                    ->where('is_active', 1)
                    ->sum('nominal');

            $potongan += $hutang;
            $gajiBersih = $gajiPokok - $potongan;

            // $existing = Gajian::where('periode_uuid', $periode->uuid)
            //     ->where('pegawai_uuid', $pegawai->uuid)
            //     ->first();

            Gajian::updateOrCreate(
                [
                    'periode_uuid' => $periode->uuid,
                    'pegawai_uuid' => $pegawai->uuid,
                ],
                [
                    'uuid' => $existing->uuid ?? Str::uuid(),

                    'hadir' => $hadir,
                    'izin' => $izin,
                    'alpha' => $alpha,

                    'gaji_pokok' => $gajiPokok,
                    'bonus' => $bonus,
                    'potongan' => $potongan,
                    
                    'gaji_bersih' => $gajiPokok + ($existing->bonus ?? $bonus) - ($existing->potongan ?? $potongan),
                ]
            );
        }
    }

    public function proses($uuid)
    {
        $periode = GajianPeriode::where('uuid', $uuid)->firstOrFail();

        if ($periode->status !== 'draft') {
            return back()->with('error', 'Sudah diproses');
        }

        DB::beginTransaction();
        try {

            $this->generateGaji($periode);

            $periode->update(['status' => 'calculated']);

            DB::commit();

            return back()->with('success', 'Gaji berhasil diproses');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($uuid, Request $request)
    {
        $periode = GajianPeriode::where('uuid', $uuid)->firstOrFail();

        // 🔥 pakai query builder, jangan langsung get()
        $query = Gajian::with(['pegawai.jabatan'])
            ->where('periode_uuid', $uuid);

        // 🔹 Filter grup
        if ($request->filled('filter_grup')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('grup_uuid', $request->filter_grup);
            });
        }

        // 🔹 Filter jabatan
        if ($request->filled('filter_jabatan')) {
            $query->whereHas('pegawai', function ($q) use ($request) {
                $q->where('jabatan_uuid', $request->filter_jabatan);
            });
        }

        $gajians = $query->get();

        $jabatans = Jabatan::all();
        $grups = Grup::all();

        return view('gajian.show', compact('periode', 'gajians', 'jabatans', 'grups'));
    }

    public function updateBonusPotongan(Request $request)
    {
        $request->validate([
            'uuid' => 'required|exists:gajians,uuid',
            'bonus' => 'nullable|numeric',
            'potongan' => 'nullable|numeric',
        ]);

        $gaji = Gajian::where('uuid', $request->uuid)->firstOrFail();

        // ❗ proteksi: tidak boleh edit kalau final
        $periode = GajianPeriode::where('uuid', $gaji->periode_uuid)->first();

        if ($periode->status == 'final') {
            return response()->json(['message' => 'Data sudah final'], 403);
        }

        $gaji->bonus = $request->bonus ?? 0;
        $gaji->potongan = $request->potongan ?? 0;

        // 🔥 hitung ulang
        $gaji->gaji_bersih = $gaji->gaji_pokok + $gaji->bonus - $gaji->potongan;

        $gaji->save();

        return response()->json(['message' => 'Berhasil update']);
    }

    public function recalculate($uuid)
    {
        $periode = GajianPeriode::where('uuid', $uuid)->firstOrFail();

        if ($periode->status !== 'calculated') {
            return back()->with('error', 'Tidak bisa recalculate');
        }

        DB::beginTransaction();
        try {

            // ❗ TIDAK PERLU DELETE
            $this->generateGaji($periode);

            DB::commit();

            return back()->with('success', 'Berhasil proses ulang');

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal recalculate');
        }
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

    public function detail($periodeUuid, $pegawaiUuid)
    {
        $periode = GajianPeriode::where('uuid', $periodeUuid)->firstOrFail();

        $gaji = Gajian::with('pegawai')
            ->where('periode_uuid', $periodeUuid)
            ->where('pegawai_uuid', $pegawaiUuid)
            ->firstOrFail();

        return view('gajian.detail', compact('periode', 'gaji'));
    }
    
    public function pdf(Request $request, $periodeUuid)
    {
        $periode = GajianPeriode::where('uuid', $periodeUuid)->firstOrFail();

        $query = Gajian::with('pegawai')
            ->where('periode_uuid', $periodeUuid);

        if ($request->pegawai_uuid) {
            $pegawaiIds = $request->pegawai_uuid;
            if (!is_array($pegawaiIds)) {
                $pegawaiIds = [$pegawaiIds];
            }
            $query->whereIn('pegawai_uuid', $pegawaiIds);
        }

        $gajians = $query->get();
        
        if ($gajians->isEmpty()) {
            return back()->with('error', 'Data gaji tidak ditemukan');
        }

        $mulai = Carbon::parse($periode->tanggal_mulai)->format('dMy');
        $selesai = Carbon::parse($periode->tanggal_selesai)->format('dMy');

        $fileName = "gajian-{$mulai}-{$selesai}.pdf";

        $pdf = Pdf::loadView('gajian.pdf', [
            'periode' => $periode,
            'gajians' => $gajians
        ]);

        return $pdf->stream($fileName);
    }
}