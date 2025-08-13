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
        // Pastikan input pegawai berupa array
        $pegawaiInput = $request->input('pegawai_uuid');
        $jabatanInput = $request->input('jabatan_uuid');

        if (!is_array($pegawaiInput)) {
            $pegawaiInput = [$pegawaiInput];
        }
        if (!is_array($jabatanInput)) {
            $jabatanInput = [$jabatanInput];
        }

        // Validasi
        $validated = $request->validate([
            'pegawai_uuid'   => 'required|array',
            'pegawai_uuid.*' => 'uuid',
            'jabatan_uuid'   => 'required|array',
            'jabatan_uuid.*' => 'uuid',
            'grup_uuid'      => 'required|uuid',
            'status'         => 'required|integer', // 1=Hadir, 2=Lembur, 3=Telat, 4=Alpha
            'tgl_absen'      => 'required|date',
        ]);

        // Debugging log
        \Log::info('Data Absensi Disimpan', [
            'pegawai_uuid' => $validated['pegawai_uuid'],
            'jabatan_uuid' => $validated['jabatan_uuid'],
            'status'       => $validated['status'],
            'tgl_absen'    => $validated['tgl_absen'],
        ]);

        // Simpan setiap absensi
        foreach ($validated['pegawai_uuid'] as $index => $pegawai_uuid) {
            Absensi::create([
                'uuid'         => \Illuminate\Support\Str::uuid(),
                'pegawai_uuid' => $pegawai_uuid,
                'jabatan_uuid' => $validated['jabatan_uuid'][$index] ?? null,
                'grup_uuid'    => $validated['grup_uuid'],
                'status'       => $validated['status'],
                'tgl_absen'    => $validated['tgl_absen'],
            ]);
        }

        return redirect()
            ->route('absensi.index', ['tgl_absen' => $request->tgl_absen])
            ->with('success', 'Absensi berhasil disimpan');
    }


    public function create()
    {
        return view('absensi.index',);
    }

    public function gantiPegawai(Request $request)
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

    public function gantiJabatan(Request $request)
    {
        $request->validate([
            'pegawai_uuid' => 'required|uuid|exists:pegawais,uuid',
            'grup_uuid' => 'required|uuid|exists:grups,uuid',
            'tgl_absen' => 'required|date',
            'jabatan_uuid' => 'required|uuid|exists:jabatans,uuid',
        ]);

        try {
            $absensi = Absensi::firstOrCreate(
                [
                    'pegawai_uuid' => $request->pegawai_uuid,
                    'tgl_absen' => $request->tgl_absen
                ],
                [
                    'uuid' => \Str::uuid(),
                    'status' => 1, // Hadir
                    'grup_uuid' => $request->grup_uuid
                ]
            );

            $absensi->jabatan_uuid = $request->jabatan_uuid; // Simpan jabatan sementara
            $absensi->save();

            return response()->json([
                'message' => 'Jabatan berhasil diganti',
                'jabatan_baru' => $absensi->jabatan->jabatan ?? '-'
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Gagal mengganti jabatan',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}