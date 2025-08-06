<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Absensi;

use App\Models\Master\Pegawai;
use App\Models\Master\Grup;
use App\Models\Master\Jabatan;

class AbsensiController extends Controller
{

    public function index(Request $request)
    {
        $tanggal = $request->input('tgl_absen', now()->toDateString());

        $riwayats = Absensi::with('pegawai')
            ->whereDate('tgl_absen', $tanggal)
            ->get();

        $jabatans = Jabatan::all();

        $grups = Grup::whereHas('pegawai', function ($q) use ($tanggal) {
            $q->whereDoesntHave('absensi', function ($sub) use ($tanggal) {
                $sub->whereDate('tgl_absen', $tanggal);
            });
        })
        ->with(['pegawai' => function ($q) use ($tanggal) {
            $q->whereDoesntHave('absensi', function ($sub) use ($tanggal) {
                $sub->whereDate('tgl_absen', $tanggal);
            });
        }])
        ->get();

        return view('absensi.index', compact(
            'jabatans', 'grups',
            'riwayats', 'tanggal'
        ));
    }


    public function store(Request $request)
    {
        $pegawaiInput = $request->input('pegawai');
        if (!is_array($pegawaiInput)) {
            $request->merge([
                'pegawai' => [$pegawaiInput]
            ]);
        }
        // dd($request->all());

        $validated = $request->validate([
            'pegawai' => 'required|array',
            'pegawai.*' => 'uuid',
            'grup_uuid' => 'required|string',
            'status' => 'required|integer', // 1=Hadir, 2=lembur, 3=telat, 4=Alfa
            'tgl_absen' => 'required|date',
        ]);
        // dd($validated);
        foreach ($validated['pegawai'] as $pegawai_uuid) {
            Absensi::create([
                'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
                'pegawai_uuid' => $pegawai_uuid,
                'grup_uuid' => $validated['grup_uuid'],
                'status' => $validated['status'],
                'tgl_absen' => $validated['tgl_absen'], 
                // 'created_by' => Auth::grup()->uuid
            ]);
        }
        // return redirect()->route('absensi.index')->with('success', 'Absensi berhasil!');
        return redirect()
        ->route('absensi.index', ['tgl_absen' => $request->tgl_absen])
        ->with('success', 'Absensi berhasil disimpan');
    }

    public function create()
    {
        return view('absensi.index',);
    }

    public function ganti(Request $request)
    {
        $request->validate([
            'pegawai_lama' => 'required|uuid|exists:pegawais,uuid',
            'pegawai_baru' => 'required|uuid|exists:pegawais,uuid',
            'tgl_absen' => 'required|date',
            'grup_uuid' => 'required|uuid|exists:grups,uuid',
        ]);

        DB::beginTransaction();
        try {
            // 1️⃣ Buat absensi pegawai lama (status 4 = Alpha/Diganti)
            $absensiLama = Absensi::firstOrCreate(
                [
                    'pegawai_uuid' => $request->pegawai_lama,
                    'tgl_absen' => $request->tgl_absen,
                ],
                [
                    'uuid' => \Str::uuid(),
                    'status' => 4, // Alpha/Diganti
                    'grup_uuid' => $request->grup_uuid,
                ]
            );

            // 2️⃣ Buat absensi pegawai baru (status 1 = Hadir)
            $absensiBaru = Absensi::firstOrCreate(
                [
                    'pegawai_uuid' => $request->pegawai_baru,
                    'tgl_absen' => $request->tgl_absen,
                ],
                [
                    'uuid' => \Str::uuid(),
                    'status' => 1, // Hadir
                    'grup_uuid' => $request->grup_uuid,
                ]
            );

            DB::commit();

            return response()->json([
                'message' => 'Pengganti disimpan',
                'nama_baru' => $absensiBaru->pegawai->nama,
                'jabatan_baru' => $absensiBaru->pegawai->jabatan->jabatan ?? '-',
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Gagal menyimpan pengganti',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}