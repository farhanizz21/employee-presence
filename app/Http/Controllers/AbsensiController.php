<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Hasil_produksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\AbsensiPeriode;
use Illuminate\Support\Str;

class AbsensiController extends Controller
{

public function index(Request $request)
{
    $bulanTahun = $request->get('bulan_tahun'); // format: YYYY-MM
    $periodeUuid = $request->get('periode_uuid');

    // Ambil semua periode (desc)
    $periodes = AbsensiPeriode::orderBy('tanggal_mulai', 'desc')->get();

    // Filter periode berdasarkan bulan-tahun jika ada
    if ($bulanTahun) {
        [$y, $m] = explode('-', $bulanTahun);
        $filteredPeriodes = $periodes->filter(function($p) use($y, $m){
            return Carbon::parse($p->tanggal_mulai)->year == $y &&
                   Carbon::parse($p->tanggal_mulai)->month == $m;
        })->values();
    } else {
        $filteredPeriodes = $periodes;
    }

    // Default periodeUuid: periode pertama hasil filter
    if (!$periodeUuid && $filteredPeriodes->isNotEmpty()) {
        $periodeUuid = $filteredPeriodes->first()->uuid;
    }

    $periode = $periodeUuid ? AbsensiPeriode::where('uuid', $periodeUuid)->first() : null;

    // Generate range tanggal
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
    $jabatans = Jabatan::all();

    $absensis = collect();
    if ($periode) {
        $absensis = Absensi::with('jabatan')
            ->where('periode_uuid', $periode->uuid)
            ->get()
            ->keyBy(fn($item) => $item->pegawai_uuid . '_' . $item->tgl_absen);
    }

    return view('absensi.index', compact(
        'periodes','filteredPeriodes','periodeUuid','bulanTahun',
        'periode','dates','pegawais','jabatans','absensis'
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


        return redirect()->back()->with('success', count($validated['pegawai_uuid']) . ' absensi berhasil disimpan');
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
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
        if ($tanggalMulai && $tanggalSelesai) {
            // Cek apakah ada periode yang overlap
            $cekOverlap = AbsensiPeriode::where(function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->where('tanggal_mulai', '<=', $tanggalSelesai)
                    ->where('tanggal_selesai', '>=', $tanggalMulai);
            })->exists();

            if ($cekOverlap) {
                return back()->with('error', 'Range tanggal sudah ada dalam periode sebelumnya!');
            }
        }
        $pegawais = Pegawai::with('jabatan')->get();
        $jabatans = Jabatan::all();

        // generate range tanggal
        $dates = [];
        $current = strtotime($tanggalMulai);
        $end = strtotime($tanggalSelesai);
        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime("+1 day", $current);
        }

        return view('absensi.rekap', compact(
            'pegawais',
            'jabatans',
            'dates',
            'tanggalMulai',
            'tanggalSelesai'
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
            'tanggal_selesai' => $tanggalSelesai,
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
                        'grup_uuid'     => $data['shift'],
                        'jabatan_uuid' => $data['jabatan_uuid'],
                        'pencapaian' => $data['pencapaian'] ?? null,
                        'periode_uuid' => $periode->uuid,
                    ]
                );
            }
        }

        foreach ($request->input('produksi', []) as $tanggal => $data) {
            Hasil_produksi::updateOrCreate(
                ['tanggal' => $tanggal],
                [
                    'hasil_pagi'  => $data['hasil_pagi'] ?? 0,
                    'hasil_malam' => $data['hasil_malam'] ?? 0,
                ]
            );
        }

        return redirect()
            ->route('absensi.index', ['periode_uuid' => $periode->uuid])
            ->with('success', 'Rekap absensi + produksi berhasil disimpan!');
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
    $tanggalMulai   = $request->get('tanggal_mulai', Carbon::now()->startOfMonth()->toDateString());
    $tanggalSelesai = $request->get('tanggal_selesai', Carbon::now()->endOfMonth()->toDateString());

    // Generate array tanggal
    $dates = [];
    $start = Carbon::parse($tanggalMulai);
    $end   = Carbon::parse($tanggalSelesai);
    while ($start->lte($end)) {
        $dates[] = $start->format('Y-m-d');
        $start->addDay();
    }

    // Ambil pegawai & data referensi
    $pegawais = Pegawai::with('jabatan')->get();
    $grups    = ['Pagi', 'Malam'];
    $jabatans = Jabatan::all();

    return view('absensi.rekap', compact(
        'tanggalMulai',
        'tanggalSelesai',
        'dates',
        'pegawais',
        'grups',
        'jabatans'
    ));
}

    public function updateCell(Request $request)
    {
        $validated = $request->validate([
            'pegawai_uuid' => 'required|uuid',
            'tanggal'      => 'required|date',
            'status'       => 'required|string',
            'grup_uuid'    => 'required|string',
            'jabatan_uuid' => 'nullable|uuid',
            'pencapaian'   => 'nullable|integer',
        ]);
$absensi = Absensi::updateOrCreate(
    [
        'pegawai_uuid' => $validated['pegawai_uuid'],
        'tgl_absen'    => $validated['tanggal'],
    ],
    [
        'status'       => $validated['status'],
        'grup_uuid'    => $validated['grup_uuid'],
        'jabatan_uuid' => $validated['jabatan_uuid'],
        'pencapaian'   => $validated['pencapaian'] ?? null,
    ]
);


        return response()->json([
            'success' => true,
            'absensi' => $absensi
        ]);
    }

    public function getPeriodeByBulanTahun(Request $request)
{
    $bulanTahun = $request->get('bulan_tahun');
    [$year, $month] = explode('-', $bulanTahun);

    $periodes = AbsensiPeriode::whereYear('tanggal_mulai', $year)
        ->whereMonth('tanggal_mulai', $month)
        ->get();

    return response()->json($periodes);
}

public function getData(Request $request)
{
    $periodeUuid = $request->get('periode_uuid');

    $absensis = Absensi::with(['pegawai','jabatan'])
        ->where('absensi_periode_uuid', $periodeUuid)
        ->get();

    return response()->json($absensis);
}

public function getAbsensiByPeriode(Request $request)
{
    $periodeUuid = $request->get('periode_uuid');
    $absensis = Absensi::with(['pegawai', 'jabatan'])
        ->where('periode_uuid', $periodeUuid)
        ->get();

    $html = view('absensi.partials.table', compact('absensis'))->render();

    return response()->json(['html' => $html]);
}

public function getAbsensiData(Request $request)
{
    $periodeUuid = $request->get('periode_uuid');
    $absensis = Absensi::with(['pegawai', 'jabatan'])
        ->where('periode_uuid', $periodeUuid)
        ->get()
        ->map(function($a) {
            return [
                'uuid' => $a->uuid,
                'pegawai' => ['nama' => $a->pegawai->nama],
                'jabatan' => $a->jabatan ? ['jabatan' => $a->jabatan->jabatan] : null,
                'grup' => $a->grup ? ['nama' => $a->grup->nama] : null,
                'status_label' => match($a->status){
                    1 => 'Hadir', 2 => 'Lembur', 3 => 'Telat', 4 => 'Alpha', default => 'Unknown'
                }
            ];
        });

    return response()->json($absensis);
}



}
