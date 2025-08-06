<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\Gajian;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\BonusPotongan;
use App\Models\Absensi;

class GajianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {        
        // if ($request->filled('search')) {
        //         $search = $request->search;
        //         $query->where('gajian', 'like', "%{$search}%");
        // }
        
        // Urutan
        // if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
        //     $query->orderBy($request->sort_by, $request->sort_order);
        // } else {
        //     $query->orderBy('created_at', 'desc'); // default sort
        // }
        
        // $periodeMulai = Carbon::now()->startOfMonth()->toDateString();
        // $periodeSelesai = Carbon::now()->endOfMonth()->toDateString();
        $periodeMulai = '2025-07-28';
        $periodeSelesai = '2025-08-03';
        
        $pegawais = Pegawai::with('jabatan','grup')->get();
        $absensi = Absensi::whereBetween('tgl_absen', [$periodeMulai, $periodeSelesai])->get();
        
        // Ambil bonus & potongan per kategori
        $bonusKehadiran = BonusPotongan::where('kode', 'bonus_keehadiran')->first();
        $bonusLembur = BonusPotongan::where('kode', 'bonus_lembur')->first();
        $potonganTelat = BonusPotongan::where('kode', 'potongan_terlambat')->first();
        
        $dataGajiDB = Gajian::with(['pegawai', 'jabatan'])->get(); 
        
        //data gaji belum dibayar
        $totalKeseluruhan = 0;
        $dataGaji = [];
        foreach ($pegawais as $pegawai) {
            $jabatan = $pegawai->jabatan;
            $grup = $pegawai->grup;

            $absensiPegawai = $absensi->where('pegawai_uuid', $pegawai->uuid);
            $jumlahHadir = $absensiPegawai->where('status', '1')->count();
            $jumlahLembur = $absensiPegawai->where('status','2' )->count();
            $jumlahTelat = $absensiPegawai->where('status', '3')->count();
            $jumlahAlpha = $absensiPegawai->where('status', '4')->count();

            // Hitung bonus kehadiran
            $totalBonusKehadiran = 0;
            if ($jumlahAlpha == 0 && $bonusKehadiran) {
                $totalBonusKehadiran += $bonusKehadiran->nominal;
            }
            
            //Hitung bonus lembur
            $totalBonusLembur = 0;
            if ($jumlahLembur > 0 && $bonusLembur) {
                $totalBonusLembur += ($jumlahLembur * $bonusLembur->nominal);
            }

            // Hitung potongan
            $totalPotongan = 0;
            if ($jumlahTelat > 0 && $potonganTelat) {
                $totalPotongan += ($jumlahTelat * $potonganTelat->nominal);
            }

            $gajiPokok = $jabatan->gaji;
            $totalGaji = $gajiPokok + $totalBonusLembur + $totalBonusKehadiran - $totalPotongan;
            
            $totalKeseluruhan += $totalGaji;

            $dataGaji[] = [
                'pegawai' => $pegawai,
                'grup' => $grup,
                'jabatan' => $jabatan,
                'gaji_pokok' => $gajiPokok,
                'bonus_lembur' => $totalBonusLembur,
                'bonus_kehadiran' => $totalBonusKehadiran,
                'total_potongan' => $totalPotongan,
                'total_gaji' => $totalGaji,
                'jumlah_hadir' => $jumlahHadir,
                'jumlah_telat' => $jumlahTelat,
                'jumlah_alpha' => $jumlahAlpha,
                'jumlah_lembur' => $jumlahLembur,
            ];
        }
    // dd($dataGaji);

    return view('gajian.index', compact('dataGaji', 'periodeMulai', 'periodeSelesai','totalKeseluruhan'));
    
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
            'keterangan'       => 'nullable|string|max:255',
        ]);

        // Simpan data ke database
        Gajian::create($validated);

        // Redirect dengan pesan sukses
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