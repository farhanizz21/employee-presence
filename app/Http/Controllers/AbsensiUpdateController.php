<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\Grup;
use Illuminate\Support\Str;

class AbsensiUpdateController extends Controller
{

    public function index(Request $request)
    {
        return view('absensiUpdate.index');
    }

    public function create(Request $request)
    {
        $query = Pegawai::with(['jabatan']);

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                // Kolom langsung dari tabel pegawai
                $q->where('nama', 'like', "%$search%");
            });
        }

        // Filter Jabatan
        if ($request->filled('filter_jabatan')) {
            $query->where('jabatan_uuid', $request->filter_jabatan);
        }

        // Filter Grup
        if ($request->filled('filter_grup')) {
            $query->where('grup_uuid', $request->filter_grup);
        }

        //filter shift
        if ($request->filled('filter_shift')) {
            $query->where('shift', $request->filter_shift);
        }

        // Urutan
        if ($request->filled('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $tanggalAbsen = $request->input('tanggal_absen', date('Y-m-d'));
        $perPage = $request->input('per_page', 10);
        $pegawais = $query->paginate($perPage)->appends($request->all());

        // Get existing absensi data for selected date
        $existingAbsensi = Absensi::where('tgl_absen', $tanggalAbsen)
            ->get()
            ->keyBy(function ($item) {
                $key = $item->pegawai_uuid;
                if ($item->is_lembur) {
                    $key .= '_long';
                }
                return $key;
            });

        $jabatans = Jabatan::all();
        $grups = Grup::all();
        // dd($existingAbsensi);
        return view('absensiUpdate.create', compact('pegawais', 'jabatans', 'grups', 'existingAbsensi', 'tanggalAbsen'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_absen' => 'required|date',
            'data' => 'required|array',
            'data.*.status' => 'required|in:1,2,3',
            'data.*.pencapaian' => 'nullable|numeric|min:0',
            'data.*.shift' => 'required|in:1,2',
            'data.*.is_lembur' => 'nullable|boolean',
        ]);

        $tanggal = $validated['tanggal_absen'];

        foreach ($validated['data'] as $uuid => $item) {

            $uuid = str_replace('_long', '', $uuid);
            $pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();

            Absensi::updateOrCreate(
                [
                    'pegawai_uuid' => $uuid,
                    'tgl_absen' => $tanggal,
                    'shift' => $item['shift'], // 🔥 WAJIB
                ],
                [
                    'uuid' => \Illuminate\Support\Str::uuid(),
                    'status' => $item['status'],
                    'pencapaian' => $item['pencapaian'] ?? 0,
                    'is_lembur' => $item['is_lembur'] ?? 0,
                ]
            );
        }

        return redirect()
            ->route('absensiUpdate.index')
            ->with('success', 'Absensi berhasil disimpan');
    }
    
}