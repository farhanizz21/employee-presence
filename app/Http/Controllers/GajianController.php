<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Gajian;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\BonusPotongan;
use App\Models\Absensi;
use App\Models\AbsensiPeriode;

class GajianController extends Controller
{
    public function index(Request $request)
    {
        // 🔽 Ambil semua periode
        $periodes = AbsensiPeriode::orderBy('tanggal_mulai', 'desc')->get();

        // 🔽 Periode dipilih (uuid)
        $periodeUuid = $request->get('periode_uuid');
        $periodeAktif = $periodeUuid
            ? AbsensiPeriode::where('uuid', $periodeUuid)->first()
            : null;

        $semua_gajian = collect();

        if ($periodeAktif) {
            $tgl_mulai   = $periodeAktif->tanggal_mulai;
            $tgl_selesai = $periodeAktif->tanggal_selesai;

            // Ambil absensi sesuai periode
            $absensi = Absensi::whereBetween('tgl_absen', [$tgl_mulai, $tgl_selesai])->get();

            // Ambil semua pegawai
            $pegawais = Pegawai::with('jabatan', 'grup')->get();

            // Hitung gajian
            $semua_gajian = $pegawais->map(function ($pegawai) use ($absensi, $tgl_mulai, $tgl_selesai) {
                // Filter absensi sesuai pegawai & periode
                $absensiPegawai = $absensi
                    ->where('pegawai_uuid', $pegawai->uuid)
                    ->whereBetween('tgl_absen', [$tgl_mulai, $tgl_selesai]);

                // Hitung status absensi
                $jumlahHadir  = $absensiPegawai->where('status', 'Masuk')->count();
                $jumlahLembur = $absensiPegawai->where('status', 'Lembur')->count();
                $jumlahTelat  = $absensiPegawai->where('status', 'Telat')->count();
                $jumlahAlpha  = $absensiPegawai->where('status', 'Alpha')->count();

                // Hitung gaji pokok sesuai jabatan tiap absensi
                $gaji_pokok_total = $absensiPegawai->reduce(function ($total, $absen) {
                    if ($absen->status === 'Alpha') {
                        return $total;
                    }
                    return $total + ($absen->jabatan ? $absen->jabatan->gaji : 0);
                }, 0);

                // Ambil aturan bonus/potongan
                $bonusKehadiran = BonusPotongan::where('kode', 'bonus_keehadiran')->first();
                $bonusLembur    = BonusPotongan::where('kode', 'bonus_lembur')->first();
                $potonganTelat  = BonusPotongan::where('kode', 'potongan_terlambat')->first();

                // Hitung bonus & potongan
                $total_bonusKehadiran = ($jumlahAlpha == 0 && $bonusKehadiran) ? $bonusKehadiran->nominal : 0;
                $total_bonusLembur    = $jumlahLembur * ($bonusLembur->nominal ?? 0);
                $total_potongan       = $jumlahTelat * ($potonganTelat->nominal ?? 0);

                // Hitung total gaji
                $total_gaji = $gaji_pokok_total + $total_bonusLembur + $total_bonusKehadiran - $total_potongan;

                $absensiHarian = $pegawai->absensi()
                    ->whereBetween('tgl_absen', [$tgl_mulai, $tgl_selesai])
                    ->with('jabatan')
                    ->orderBy('tgl_absen', 'asc')
                    ->get()
                    ->map(function ($absen) {
                        return [
                            'tanggal' => Carbon::parse($absen->tgl_absen)->format('l, d-M-y'),
                            'jabatan' => $absen->jabatan ? $absen->jabatan->jabatan : '-',
                            'gaji'    => $absen->jabatan ? $absen->jabatan->gaji : 0,
                            'status'  => $absen->status,
                        ];
                    });
                return [
                    'pegawai'         => $pegawai,
                    'grup'            => $pegawai->grup,
                    'jabatan'         => $pegawai->jabatan,
                    'periode_mulai'   => $tgl_mulai,
                    'periode_selesai' => $tgl_selesai,
                    'jumlah_hadir'    => $jumlahHadir,
                    'jumlah_telat'    => $jumlahTelat,
                    'jumlah_alpha'    => $jumlahAlpha,
                    'jumlah_lembur'   => $jumlahLembur,
                    'gaji_pokok'      => $gaji_pokok_total, // sudah total dari absensi
                    'bonus_lembur'    => $total_bonusLembur,
                    'bonus_kehadiran' => $total_bonusKehadiran,
                    'total_potongan'  => $total_potongan,
                    'total_gaji'      => $total_gaji,
                    'detail_absensi'  => $absensiHarian,
                    'status_gajian'   => '0'
                ];
            });
        }


        return view('gajian.index', [
            'semua_gajian' => $semua_gajian,
            'periodes'     => $periodes,
            'periodeUuid'  => $periodeUuid,
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hilangkan titik dari input nominal supaya bisa divalidasi sebagai integer
        $request->merge([
            'gaji_pokok'      => str_replace('.', '', $request->gaji_pokok),
            'bonus_lembur'    => str_replace('.', '', $request->bonus_lembur),
            'bonus_kehadiran' => str_replace('.', '', $request->bonus_kehadiran),
            'total_potongan'  => str_replace('.', '', $request->total_potongan),
            'total_gaji'      => str_replace('.', '', $request->total_gaji),
        ]);

        // Validasi request
        $validated = $request->validate([
            'pegawai_uuid'     => 'required|uuid|exists:pegawais,uuid',
            'jabatan_uuid'     => 'required|uuid|exists:jabatans,uuid',
            'gaji_pokok'       => 'required|integer|min:0',
            'bonus_lembur'     => 'nullable|integer|min:0',
            'bonus_kehadiran'  => 'nullable|integer|min:0',
            'total_potongan'   => 'nullable|integer|min:0',
            'total_gaji'       => 'required|integer|min:0',
            'jumlah_hadir'     => 'nullable|integer|min:0',
            'jumlah_lembur'    => 'nullable|integer|min:0',
            'jumlah_telat'     => 'nullable|integer|min:0',
            'jumlah_alpha'     => 'nullable|integer|min:0',
        ]);

        // Simpan data ke database dengan UUID otomatis
        Gajian::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'pegawai_uuid'     => $validated['pegawai_uuid'],
            'jabatan_uuid'     => $validated['jabatan_uuid'],
            'gaji_pokok'       => $validated['gaji_pokok'],
            'bonus_lembur'     => $validated['bonus_lembur'],
            'bonus_kehadiran'  => $validated['bonus_kehadiran'],
            'total_potongan'   => $validated['total_potongan'],
            'total_gaji'       => $validated['total_gaji'],
            'jumlah_hadir'     => $validated['jumlah_hadir'],
            'jumlah_lembur'    => $validated['jumlah_lembur'],
            'jumlah_telat'     => $validated['jumlah_telat'],
            'jumlah_alpha'     => $validated['jumlah_alpha'],
        ]);

        return redirect()
            ->route('gajian.index')
            ->with('success', 'Data gajian berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Gajian $gajian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gajian $gajian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Gajian $gajian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gajian $gajian)
    {
        //
    }

    public function cetakSlip($uuid)
    {
        $gaji = Gajian::with(['pegawai', 'jabatan'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        $pdf = Pdf::loadView('gajian.slip', compact('gaji'))
            ->setPaper('A4', 'portrait');
        $nama = strtolower(trim($gaji->pegawai->nama));
        $tanggal = $gaji->created_at->translatedFormat('dmy');

        // Download langsung
        return $pdf->stream('slip-gaji_' . $nama . '_' . $tanggal . '.pdf');
    }
}
