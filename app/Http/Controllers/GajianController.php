<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Models\Gajian;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\BonusPotongan;
use App\Models\Absensi;

class GajianController extends Controller
{
    // public function index(Request $request)
    // {
    //     $periodeMulai = '2025-07-28';
    //     $periodeSelesai = '2025-08-03';
        
    //     $pegawais = Pegawai::with('jabatan','grup')->get();
    //     $absensi = Absensi::whereBetween('tgl_absen', [$periodeMulai, $periodeSelesai])->get();
        
    //     // Ambil bonus & potongan per kategori
    //     $bonusKehadiran = BonusPotongan::where('kode', 'bonus_keehadiran')->first();
    //     $bonusLembur = BonusPotongan::where('kode', 'bonus_lembur')->first();
    //     $potonganTelat = BonusPotongan::where('kode', 'potongan_terlambat')->first();
        
    //     $dataGajiDB = Gajian::with(['pegawai', 'jabatan'])->get(); 
        
    //     //data gaji belum dibayar
    //     $totalKeseluruhan = 0;
    //     $dataGaji = [];
    //     foreach ($pegawais as $pegawai) {
    //         $jabatan = $pegawai->jabatan;
    //         $grup = $pegawai->grup;

    //         $absensiPegawai = $absensi->where('pegawai_uuid', $pegawai->uuid);
    //         $jumlahHadir = $absensiPegawai->where('status', '1')->count();
    //         $jumlahLembur = $absensiPegawai->where('status','2' )->count();
    //         $jumlahTelat = $absensiPegawai->where('status', '3')->count();
    //         $jumlahAlpha = $absensiPegawai->where('status', '4')->count();

    //         // Hitung bonus kehadiran
    //         $totalBonusKehadiran = 0;
    //         if ($jumlahAlpha == 0 && $bonusKehadiran) {
    //             $totalBonusKehadiran += $bonusKehadiran->nominal;
    //         }
            
    //         //Hitung bonus lembur
    //         $totalBonusLembur = 0;
    //         if ($jumlahLembur > 0 && $bonusLembur) {
    //             $totalBonusLembur += ($jumlahLembur * $bonusLembur->nominal);
    //         }

    //         // Hitung potongan
    //         $totalPotongan = 0;
    //         if ($jumlahTelat > 0 && $potonganTelat) {
    //             $totalPotongan += ($jumlahTelat * $potonganTelat->nominal);
    //         }

    //         $gajiPokok = $jabatan->gaji;
    //         $totalGaji = $gajiPokok + $totalBonusLembur + $totalBonusKehadiran - $totalPotongan;
            
    //         $totalKeseluruhan += $totalGaji;

    //         $dataGaji[] = [
    //             'pegawai' => $pegawai,
    //             'grup' => $grup,
    //             'jabatan' => $jabatan,
    //             'gaji_pokok' => $gajiPokok,
    //             'bonus_lembur' => $totalBonusLembur,
    //             'bonus_kehadiran' => $totalBonusKehadiran,
    //             'total_potongan' => $totalPotongan,
    //             'total_gaji' => $totalGaji,
    //             'jumlah_hadir' => $jumlahHadir,
    //             'jumlah_telat' => $jumlahTelat,
    //             'jumlah_alpha' => $jumlahAlpha,
    //             'jumlah_lembur' => $jumlahLembur,
    //         ];
    //     }
    //     // dd($dataGaji);

    //     return view('gajian.index', compact('dataGaji', 'periodeMulai', 'periodeSelesai','totalKeseluruhan'));
    
    // }

    public function index()
    {
        $today = now();

        // 1️⃣ Ambil data gajian yang sudah dibayar
        $sudah_gajian = Gajian::with('pegawai', 'pegawai.jabatan')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($gajian) {
                return [
                    'pegawai'         => $gajian->pegawai,
                    'grup'            => $gajian->pegawai->grup,
                    'jabatan'         => $gajian->pegawai->jabatan,
                    'periode_mulai'   => $gajian->created_at->copy()->startOfDay()->toDateString(),
                    'periode_selesai' => $gajian->created_at->copy()->endOfDay()->toDateString(),
                    'jumlah_hadir'    => $gajian->jumlah_hadir,
                    'jumlah_telat'    => $gajian->jumlah_telat,
                    'jumlah_alpha'    => $gajian->jumlah_alpha,
                    'jumlah_lembur'   => $gajian->jumlah_lembur,
                    'gaji_pokok'      => $gajian->gaji_pokok,
                    'bonus_lembur'    => $gajian->bonus_lembur,
                    'bonus_kehadiran' => $gajian->bonus_kehadiran,
                    'total_potongan'  => $gajian->total_potongan,
                    'total_gaji'      => $gajian->total_gaji,
                    'status_gajian'   => '1'
                ];
            });
            // dd($sudah_gajian);

        // 2️⃣ Ambil gajian terakhir per pegawai
        $lastGajian = Gajian::select(
                'pegawai_uuid', 
                DB::raw('MAX(created_at) as last_gajian')
            )
            ->groupBy('pegawai_uuid')
            ->get()
            ->keyBy('pegawai_uuid');

        // 3️⃣ Ambil data absensi untuk periode aktif (gajian terakhir → hari ini)
        $absensi = Absensi::whereDate('tgl_absen', '<=', $today)->get();

        // 4️⃣ Ambil semua pegawai
        $pegawais = Pegawai::with('jabatan','grup')->get();

        // 5️⃣ Hitung pegawai yang belum gajian
        $belum_gajian = $pegawais->filter(function ($pegawai) use ($sudah_gajian) {
                return !$sudah_gajian->contains('pegawai.uuid', $pegawai->uuid);
            })
            ->map(function ($pegawai) use ($lastGajian, $absensi, $today) {

                // Periode aktif
                $lastDate = $lastGajian[$pegawai->uuid]->last_gajian ?? null;
                $tgl_mulai = $lastDate 
                    ? Carbon::parse($lastDate)->addDay()->toDateString()
                    : $absensi->where('pegawai_uuid', $pegawai->uuid)->min('tgl_absen'); // awal absensi
                $tgl_selesai = $today->toDateString();

                // Filter absensi sesuai periode
                $absensiPegawai = $absensi
                    ->where('pegawai_uuid', $pegawai->uuid)
                    ->whereBetween('tgl_absen', [$tgl_mulai, $tgl_selesai]);

                // Hitung jumlah hari
                $jumlahHadir  = $absensiPegawai->where('status', '1')->count();
                $jumlahLembur = $absensiPegawai->where('status', '2')->count();
                $jumlahTelat  = $absensiPegawai->where('status', '3')->count();
                $jumlahAlpha  = $absensiPegawai->where('status', '4')->count();

                // Ambil bonus & potongan per kategori
                $bonusKehadiran = BonusPotongan::where('kode', 'bonus_keehadiran')->first();
                $bonusLembur = BonusPotongan::where('kode', 'bonus_lembur')->first();
                $potonganTelat = BonusPotongan::where('kode', 'potongan_terlambat')->first();
                
                // Hitung bonus kehadiran
                $total_bonusKehadiran = 0;
                if ($jumlahAlpha == 0 && $bonusKehadiran) {
                    $total_bonusKehadiran += $bonusKehadiran->nominal;
                }
                
                //Hitung bonus lembur
                $total_bonusLembur = 0;
                if ($jumlahLembur > 0 && $bonusLembur) {
                    $total_bonusLembur += ($jumlahLembur * $bonusLembur->nominal);
                }

                // Hitung potongan
                $total_potongan = 0;
                if ($jumlahTelat > 0 && $potonganTelat) {
                    $total_potongan += ($jumlahTelat * $potonganTelat->nominal);
                }

                $jabatan = $pegawai->jabatan;
                $gaji_pokok = $jabatan->gaji;
                $total_gaji = $gaji_pokok + $total_bonusLembur + $total_bonusKehadiran - $total_potongan;
                
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
                    'gaji_pokok'      => $gaji_pokok,
                    'bonus_lembur'    => $total_bonusLembur,
                    'bonus_kehadiran' => $total_bonusKehadiran,
                    'total_potongan'  => $total_potongan,
                    'total_gaji'      => $total_gaji,
                    'status_gajian'   => '0'
                ];
            })
            ->values();
            
            // dd($belum_gajian);
        $semua_gajian = collect($belum_gajian)->merge($sudah_gajian);
        return view('gajian.index', [
            'semua_gajian' => $semua_gajian,
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
}