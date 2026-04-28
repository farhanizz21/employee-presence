<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\Grup;
use App\Models\Absensi;
use App\Models\ProduksiHarian;

class AbsensiUpdateController extends Controller
{

    public function index(Request $request)
    {
        $tanggal = request('tanggal') ?? now()->toDateString();

        $absensis = Absensi::with(['pegawai','jabatan'])
            ->whereDate('tgl_absen', $tanggal)
            ->get();

        $hadir = $absensis->where('status', 1)->count();
        $izin  = $absensis->where('status', 2)->count();
        $alpha = $absensis->where('status', 3)->count();

        return view('absensiUpdate.index', compact(
            'absensis','hadir','izin','alpha'
        ));
        // return view('absensiUpdate.index');
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
        
        $existingProduksi = ProduksiHarian::where('tanggal', $tanggalAbsen)->get()->keyBy('shift');

        $jabatans = Jabatan::all();
        $grups = Grup::all();
        // dd($existingAbsensi);
        return view('absensiUpdate.create', compact('pegawais', 'jabatans', 'grups', 'existingAbsensi', 'existingProduksi', 'tanggalAbsen'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'tanggal_absen' => 'required|date',
            'mesin_status' => 'required|array',
            'mesin_status.*' => 'required|in:0,1',

            'data' => 'required|array',
            'data.*.status' => 'required|in:1,2,3',
            'data.*.pencapaian' => 'nullable|numeric|min:0',
            'data.*.shift' => 'required|in:1,2',
            'data.*.is_lembur' => 'nullable|boolean',
            
            'data.*.jabatan_uuid' => 'nullable|uuid',
            'data.*.grup_uuid' => 'nullable|uuid',
        ]);

        $tanggal = $validated['tanggal_absen'];
        $mesin_status = $validated['mesin_status'];

        
        $totalProduksiPerShift = [];
        //hitung total produksi per shift untuk update/insert ke ProduksiHarian
        foreach ($validated['data'] as $uuid => $item) {
            $shift = $item['shift'];
            $pencapaian = $item['pencapaian'] ?? 0;
            $totalProduksiPerShift[$shift] = ($totalProduksiPerShift[$shift] ?? 0) + $pencapaian;
            }
            
            // dd($validated);
        $produksiPerShift = [];
        foreach ($totalProduksiPerShift as $shift => $totalProduksi) {
            $mesinStatus = $validated['mesin_status'][$shift] ?? 0;
            $produksi = ProduksiHarian::firstOrNew([
                'tanggal' => $tanggal,
                'shift' => $shift,
            ]);
            if (!$produksi->exists) {
                $produksi->uuid = Str::uuid();
            }
            $produksi->mesin_status = $mesinStatus;
            $produksi->total_produksi = $totalProduksi;

            $produksi->save();

            $produksiPerShift[$shift] = $produksi->uuid;
        }

        foreach ($validated['data'] as $uuid => $item) {
            $uuid = str_replace('_long', '', $uuid);
            $pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();

            $shift = $item['shift'];

            Absensi::updateOrCreate(
                [
                    'pegawai_uuid' => $uuid,
                    'tgl_absen' => $tanggal,
                    'shift' => $shift,
                ],
                [
                    'uuid' => Str::uuid(),
                    'produksi_uuid' => $produksiPerShift[$shift] ?? null,
                    'status' => $item['status'],
                    'pencapaian' => $item['pencapaian'] ?? 0,
                    'is_lembur' => $item['is_lembur'] ?? 0,
                    'jabatan_uuid' => $item['jabatan_uuid'] ?? $pegawai->jabatan_uuid,
                    'grup_uuid' => $item['grup_uuid'] ?? $pegawai->grup_uuid,
                ]
            );
        }

        return redirect()
            ->route('absensiUpdate.index')
            ->with('success', 'Absensi berhasil disimpan');
    }
    
}