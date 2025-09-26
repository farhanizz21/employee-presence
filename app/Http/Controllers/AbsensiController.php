<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Absensi;

use App\Models\Master\Pegawai;
use App\Models\Master\Grup;
use App\Models\Master\Jabatan;
use App\Models\AbsensiPeriode;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{

    public function index(Request $request)
{
    $periodeUuid = $request->get('periode_uuid');
    $periodes = AbsensiPeriode::orderBy('tanggal_mulai', 'desc')->get();

    // Default pakai periode terbaru
    if (!$periodeUuid && $periodes->isNotEmpty()) {
        $periodeUuid = $periodes->first()->uuid;
    }

    $periode = AbsensiPeriode::where('uuid', $periodeUuid)->first();

    $dates = [];
    if ($periode) {
        $start = Carbon::parse($periode->tanggal_mulai);
        $end   = Carbon::parse($periode->tanggal_selesai);

        while ($start->lte($end)) {
            $dates[] = $start->format('Y-m-d');
            $start->addDay();
        }
    }

    $pegawais = Pegawai::all();
    $grups    = Grup::all();

    $absensis = collect(); // kosong dulu
    if ($periode) {
        $absensis = Absensi::where('periode_uuid', $periode->uuid)
    ->get()
    ->keyBy(fn($item) => $item->pegawai_uuid.'_'.$item->tgl_absen);

    }

    return view('absensi.index', compact(
        'periodes',
        'periodeUuid',
        'periode',
        'dates',
        'pegawais',
        'grups',
        'absensis'
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
            
            'jabatan_uuid'   => 'required|array',
            'pegawai_uuid.*' => 'required|uuid|exists:pegawais,uuid',
            'jabatan_uuid.*' => 'nullable|uuid|exists:jabatans,uuid',
            'grup_uuid'      => 'required|uuid|exists:grups,uuid',
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
            $sudahAda = Absensi::where('pegawai_uuid', $pegawai_uuid)
            ->whereDate('tgl_absen', $validated['tgl_absen'])
            ->where('grup_uuid', $validated['grup_uuid'])
            ->exists();

            if ($sudahAda) {
                \Log::warning("Absensi duplikat dicegah untuk pegawai $pegawai_uuid pada {$validated['tgl_absen']}");
        continue; // skip pegawai ini
    }

    Absensi::create([
        'uuid'         => \Illuminate\Support\Str::uuid(),
        'pegawai_uuid' => $pegawai_uuid,
        'jabatan_uuid' => $validated['jabatan_uuid'][$index] ?? null,
        'grup_uuid'    => $validated['grup_uuid'],
        'status'       => $validated['status'],
        'tgl_absen'    => $validated['tgl_absen'],
    ]);
}


return redirect()->back()->with('success', count($validated['pegawai_uuid']).' absensi berhasil disimpan');

}


// AbsensiController@create
public function create(Request $request)
{
    $tanggal = $request->input('tgl_absen', date('Y-m-d'));

    $pegawais = Pegawai::whereDoesntHave('absensis', function ($q) use ($tanggal) {
        $q->whereDate('tgl_absen', $tanggal);
    })->get();

    return view('absensi.create', compact('pegawais', 'tanggal'));
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
                'uuid' 
                => \Str::uuid(),
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

    public function updateStatus(Request $request, $uuid)
    {
        $request->validate([
            'status' => 'required|integer|in:1,2,3,4',
        ]);

        $absensi = Absensi::where('uuid', $uuid)->firstOrFail();
        $absensi->status = $request->status;
        $absensi->save();

        return response()->json([
            'success' => true,
            'message' => 'Status absensi berhasil diperbarui!',
            'status_label' => match ($absensi->status) {
                1 => 'Hadir',
                2 => 'Lembur',
                3 => 'Telat',
                4 => 'Alpha',
                default => 'Unknown'
            },
            'status_class' => match ($absensi->status) {
                1 => 'success',
                2 => 'primary',
                3 => 'warning',
                4 => 'danger',
                default => 'secondary'
            }
        ]);
    }

    public function formRekap(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', date('Y-m-d'));
        $tanggalSelesai = $request->input('tanggal_selesai', date('Y-m-d'));

    $pegawais = Pegawai::with('grup')->get(); // ambil juga default grup/jobdesk
    $grups = Grup::all();

    // generate range tanggal
    $dates = [];
    $current = strtotime($tanggalMulai);
    $end = strtotime($tanggalSelesai);
    while ($current <= $end) {
        $dates[] = date('Y-m-d', $current);
        $current = strtotime("+1 day", $current);
    }

    return view('absensi.rekap', compact(
        'pegawais','grups','dates',
        'tanggalMulai','tanggalSelesai'
    ));
}


public function simpanRekap(Request $request)
{
    $tanggalMulai   = $request->input('tanggal_mulai');
    $tanggalSelesai = $request->input('tanggal_selesai');
    $periodeUuid = Str::uuid()->toString();  // Generate UUID
    $periode = AbsensiPeriode::create([
    'uuid'           => $periodeUuid,
    'nama_periode'   => 'Periode ' . $tanggalMulai . ' s/d ' . $tanggalSelesai,
    'tanggal_mulai'  => $tanggalMulai,
    'tanggal_selesai'=> $tanggalSelesai,
]);



    // Loop input absensi pegawai
    foreach ($request->input('absensi', []) as $pegawaiUuid => $tanggalData) {
        $pegawai = \App\Models\Master\Pegawai::with('grup')->find($pegawaiUuid);

        foreach ($tanggalData as $tanggal => $data) {
            Absensi::updateOrCreate(
                [
                    'pegawai_uuid' => $pegawaiUuid,
                    'tgl_absen'    => $tanggal,
                    'periode_uuid' => $periode->uuid,
                ],
                [
                    'uuid'      => Str::uuid()->toString(),
                    'status'    => $data['status'] ?? 'Alpha',
                    'shift'     => $data['shift'] ?? ($pegawai->default_shift ?? 'Pagi'),
                    'grup_uuid' => $data['grup_uuid'] ?? $pegawai->grup_uuid,
                    'pencapaian'=> $data['pencapaian_kg'] ?? null,
                    'periode_uuid' => $periode->uuid,
                ]
            );
        }
    }

    return redirect()
    ->route('absensi.index', ['periode_uuid' => $periode->uuid])
    ->with('success','Rekap absensi berhasil disimpan!');
}

public function gantiShiftJobdesk(Request $request)
{
    $request->validate([
        'pegawai_uuid' => 'required|uuid|exists:pegawais,uuid',
        'tanggal' => 'required|date',
        'shift' => 'required|string',
        'grup_uuid' => 'nullable|uuid|exists:grups,uuid',
    ]);

    \App\Models\Absensi::updateOrCreate(
        [
            'pegawai_uuid' => $request->pegawai_uuid,
            'tgl_absen' => $request->tanggal,
        ],
        [
            'uuid' => Str::uuid()->toString(),
            'status' => 'Masuk', // default saja
            'shift' => $request->shift,
            'grup_uuid' => $request->grup_uuid,
        ]
    );

    return redirect()->back()->with('success', 'Shift & Jobdesk berhasil diubah!');
}

public function update(Request $request)
{
    $pegawai = Pegawai::findOrFail($request->id);
    $pegawai->shift = $request->shift;
    $pegawai->jobdesk = $request->jobdesk;
    $pegawai->status = $request->status;
    $pegawai->save();

    return response()->json($pegawai);
}


public function rekap(Request $request)
{
    // ambil range tanggal dari request
    $tanggalMulai   = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
    $tanggalSelesai = $request->get('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());

    // bikin array tanggal
    $dates = [];
    $start = Carbon::parse($tanggalMulai);
    $end   = Carbon::parse($tanggalSelesai);
    while ($start->lte($end)) {
        $dates[] = $start->format('Y-m-d');
        $start->addDay();
    }

    // data referensi pegawai & grup
    $pegawais = Pegawai::with('grup')->get();
    $grups    = Grup::all();

    return view('absensi.rekap', compact(
        'tanggalMulai',
        'tanggalSelesai',
        'dates',
        'pegawais',
        'grups'
    ));
}


public function updateCell(Request $request)
{
    $validated = $request->validate([
        'pegawai_uuid' => 'required',
        'tanggal'      => 'required|date',
        'status'       => 'required',
        'shift'        => 'required',
        'grup_uuid'    => 'nullable'
    ]);

    $absensi = Absensi::updateOrCreate(
        [
            'pegawai_uuid' => $validated['pegawai_uuid'],
            'tgl_absen'    => $validated['tanggal'],
        ],
        [
            'uuid'       => Str::uuid()->toString(), // generate uuid jika insert baru
            'status'     => $validated['status'],
            'shift'      => $validated['shift'],
            'grup_uuid'  => $validated['grup_uuid'],
        ]
    );

    return response()->json([
        'success' => true,
        'absensi' => $absensi
    ]);
}

}