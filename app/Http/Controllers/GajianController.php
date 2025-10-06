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
use App\Models\GajianDetail;
use App\Models\AbsensiPeriode;

class GajianController extends Controller
{
    public function index(Request $request)
    {
        $periodes = AbsensiPeriode::orderBy('tanggal_mulai', 'desc')->get();
        $periodeUuid = $request->get('periode_uuid');
        $periodeAktif = $periodeUuid
            ? AbsensiPeriode::where('uuid', $periodeUuid)->first()
            : null;

        $semua_gajian = collect();

        if ($periodeAktif) {
            $tgl_mulai   = $periodeAktif->tanggal_mulai;
            $tgl_selesai = $periodeAktif->tanggal_selesai;

            // 1️⃣ Ambil data gajian yang sudah dibayar di periode ini

            $sudah_gajian = Gajian::with('pegawai')
                ->where('periode_uuid', $periodeUuid)
                ->get()
                ->mapWithKeys(function ($gajian) {
                    $pegawai = $gajian->pegawai;
                    $absensiHarian = $pegawai->absensi()
                        ->with('jabatan')
                        ->whereBetween('tgl_absen', [
                            $gajian->periode->tanggal_mulai,
                            $gajian->periode->tanggal_selesai
                        ])
                        ->orderBy('tgl_absen', 'asc')
                        ->get()
                        ->map(function ($absen) {
                            $jabatan = $absen->jabatan;
                            $gajiHari = 0;
                            if ($jabatan) {
                                if ($jabatan->harian == 1) {
                                    $gajiHari = $absen->grup_uuid == 'Pagi'
                                        ? ($jabatan->gaji_pagi ?? 0)
                                        : ($jabatan->gaji_malam ?? 0);
                                } elseif ($jabatan->harian == 2) {
                                    $gajiHari = $absen->grup_uuid == 'Pagi'
                                        ? ($absen->pencapaian ?? 0) * ($jabatan->gaji_pagi ?? 0)
                                        : ($absen->pencapaian ?? 0) * ($jabatan->gaji_malam ?? 0);
                                }
                            }

                            return [
                                'tanggal'    => \Carbon\Carbon::parse($absen->tgl_absen)->format('l, d-M-y'),
                                'jabatan'    => $jabatan ? $jabatan->jabatan : '-',
                                'gaji'       => $gajiHari,
                                'grup_uuid'  => $absen->grup_uuid,
                                'status'     => $absen->status,
                                'pencapaian' => $absen->pencapaian ?? 0,
                            ];
                        });
                    return [
                        $gajian->pegawai_uuid => [
                            'pegawai'         => $gajian->pegawai,
                            'uuid'          => $gajian->uuid,
                            'grup'            => $gajian->pegawai->grup_uuid,
                            'jabatan'         => $gajian->jabatan,
                            'periode_mulai'   => $gajian->periode->tanggal_mulai,
                            'periode_selesai' => $gajian->periode->tanggal_selesai,
                            'jumlah_hadir'    => $gajian->jumlah_hadir,
                            'jumlah_telat'    => $gajian->jumlah_telat,
                            'jumlah_alpha'    => $gajian->jumlah_alpha,
                            'jumlah_lembur'   => $gajian->jumlah_lembur,
                            'gaji_pokok'      => $gajian->gaji_pokok,
                            'bonus_lembur'    => $gajian->bonus_lembur,
                            'bonus_kehadiran' => $gajian->bonus_kehadiran,
                            'total_potongan'  => $gajian->total_potongan,
                            'total_gaji'      => $gajian->total_gaji,
                            'detail_absensi'  => $absensiHarian,
                            'status_gajian'   => '1',
                        ]
                    ];
                });

            // 2️⃣ Ambil absensi pegawai untuk periode
            $pegawais = Pegawai::get();

            $hasilHitung = $pegawais->mapWithKeys(function ($pegawai) use ($periodeAktif, $tgl_mulai, $tgl_selesai) {
                $absensiPegawai = $pegawai->absensi()
                    ->with('jabatan')
                    ->whereBetween('tgl_absen', [$tgl_mulai, $tgl_selesai])
                    ->get();

                if ($absensiPegawai->isEmpty()) {
                    return [$pegawai->uuid => null];
                }

                // hitung total gaji dsb (sama seperti perhitunganmu tadi)
                $jumlahHadir = $absensiPegawai->whereIn('status', ['Masuk', 'Lembur', 'Telat'])->count();
                $jumlahLembur = $absensiPegawai->where('status', 'Lembur')->count();
                $jumlahTelat = $absensiPegawai->where('status', 'Telat')->count();
                $jumlahAlpha = $absensiPegawai->where('status', 'Alpha')->count();

                $gaji_pokok_total = $absensiPegawai->whereIn('status', ['Masuk', 'Lembur', 'Telat'])
                    ->sum(function ($absen) {
                        $jabatan = $absen->jabatan;
                        if (!$jabatan) return 0;
                        if ($jabatan->harian == 1) {
                            return $absen->grup_uuid == 'Pagi'
                                ? $jabatan->gaji_pagi ?? 0
                                : $jabatan->gaji_malam ?? 0;
                        } elseif ($jabatan->harian == 2) {
                            return ($absen->pencapaian ?? 0) * (
                                $absen->grup_uuid == 'Pagi'
                                ? $jabatan->gaji_pagi ?? 0
                                : $jabatan->gaji_malam ?? 0
                            );
                        }
                        return 0;
                    });

                // aturan bonus/potongan (boleh dipanggil dari helper)
                $bonusLembur    = BonusPotongan::where('kode', 'bonus_lembur')->first();
                $potonganTelat  = BonusPotongan::where('kode', 'potongan_terlambat')->first();
                $bonusKehadiran = BonusPotongan::where('kode', 'bonus_kehadiran')->first();

                $total_bonusLembur = $jumlahLembur * ($bonusLembur->nominal ?? 0);
                $total_potongan    = $jumlahTelat * ($potonganTelat->nominal ?? 0);
                $total_bonusKehadiran = 0;

                // === Bonus Kehadiran Berkelanjutan ===
                if ($bonusKehadiran) {
                    $nominalBonus = (int) ($bonusKehadiran->nominal ?? 0);

                    // hitung jumlah hari kerja di periode aktif
                    $hariPeriodeSekarang = \Carbon\Carbon::parse($tgl_mulai)->diffInDays(\Carbon\Carbon::parse($tgl_selesai)) + 1;

                    // ambil periode sebelumnya (berdasarkan tanggal_selesai < periode aktif)
                    $periodeSebelumnya = \App\Models\AbsensiPeriode::where('tanggal_selesai', '<', $tgl_mulai)
                        ->orderBy('tanggal_selesai', 'desc')
                        ->first();

                    $hadirPenuhSekarang = false;
                    $hadirPenuhSebelumnya = false;

                    // cek kehadiran periode sekarang
                    if ($jumlahHadir >= $hariPeriodeSekarang && $jumlahAlpha == 0) {
                        $hadirPenuhSekarang = true;
                    }

                    // cek kehadiran periode sebelumnya
                    if ($periodeSebelumnya) {
                        $absensiSebelum = $pegawai->absensi()
                            ->whereBetween('tgl_absen', [$periodeSebelumnya->tanggal_mulai, $periodeSebelumnya->tanggal_selesai])
                            ->get();

                        $hariPeriodeSebelum = \Carbon\Carbon::parse($periodeSebelumnya->tanggal_mulai)
                            ->diffInDays(\Carbon\Carbon::parse($periodeSebelumnya->tanggal_selesai)) + 1;

                        $jumlahHadirSebelum = $absensiSebelum->whereIn('status', ['Masuk', 'Telat', 'Lembur'])->count();
                        $jumlahAlphaSebelum = $absensiSebelum->where('status', 'Alpha')->count();

                        if ($jumlahHadirSebelum >= $hariPeriodeSebelum && $jumlahAlphaSebelum == 0) {
                            $hadirPenuhSebelumnya = true;
                        }
                    }

                    // jika hadir penuh di dua periode berturut-turut, aktifkan bonus
                    if ($hadirPenuhSekarang && $hadirPenuhSebelumnya) {
                        $total_bonusKehadiran = $nominalBonus;
                    }
                }


                $total_gaji = $gaji_pokok_total + $total_bonusLembur + $total_bonusKehadiran - $total_potongan;
                $absensiHarian = $pegawai->absensi()
                    ->whereBetween('tgl_absen', [$tgl_mulai, $tgl_selesai])
                    ->with('jabatan')
                    ->orderBy('tgl_absen', 'asc')
                    ->get()
                    ->map(function ($absen) {
                        $jabatan = $absen->jabatan;
                        $gajiHari = 0;
                        if ($jabatan) {
                            if ($jabatan->harian == 1) {
                                $gajiHari = $absen->grup_uuid == 'Pagi'
                                    ? ($jabatan->gaji_pagi ?? 0)
                                    : ($jabatan->gaji_malam ?? 0);
                            } elseif ($jabatan->harian == 2) {
                                $gajiHari = $absen->grup_uuid == 'Pagi'
                                    ? ($absen->pencapaian ?? 0) * ($jabatan->gaji_pagi ?? 0)
                                    : ($absen->pencapaian ?? 0) * ($jabatan->gaji_malam ?? 0);
                            }
                        }

                        return [
                            'tanggal'    => \Carbon\Carbon::parse($absen->tgl_absen)->format('l, d-M-y'),
                            'jabatan'    => $jabatan ? $jabatan->jabatan : '-',
                            'gaji'       => $gajiHari,
                            'grup_uuid'  => $absen->grup_uuid,
                            'status'     => $absen->status,
                            'pencapaian' => $absen->pencapaian ?? 0,
                        ];
                    });

                return [
                    $pegawai->uuid => [
                        'pegawai'         => $pegawai,
                        'grup'            => $pegawai->grup_uuid,
                        'jabatan'         => $absensiPegawai->last()->jabatan ?? null,
                        'periode_mulai'   => $tgl_mulai,
                        'periode_selesai' => $tgl_selesai,
                        'jumlah_hadir'    => $jumlahHadir,
                        'jumlah_telat'    => $jumlahTelat,
                        'jumlah_alpha'    => $jumlahAlpha,
                        'jumlah_lembur'   => $jumlahLembur,
                        'gaji_pokok'      => $gaji_pokok_total,
                        'bonus_lembur'    => $total_bonusLembur,
                        'bonus_kehadiran' => $total_bonusKehadiran,
                        'total_potongan'  => $total_potongan,
                        'total_gaji'      => $total_gaji,
                        'detail_absensi'  => $absensiHarian,
                        'status_gajian'   => '0',
                    ]
                ];
            });

            // 3️⃣ Gabungkan: data gajians override data absensi
            $semua_gajian = $hasilHitung->filter()->merge($sudah_gajian);
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
            'periode_uuid'     => 'required|uuid|exists:absensi_periode,uuid',
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
            'periode_uuid'     => $validated['periode_uuid'],
            'bonus_kehadiran'  => $validated['bonus_kehadiran'],
            'total_potongan'   => $validated['total_potongan'],
            'total_gaji'       => $validated['total_gaji'],
            'jumlah_hadir'     => $validated['jumlah_hadir'],
            'jumlah_lembur'    => $validated['jumlah_lembur'],
            'jumlah_telat'     => $validated['jumlah_telat'],
            'jumlah_alpha'     => $validated['jumlah_alpha'],
        ]);

        return redirect()
            ->to('/gajian?periode_uuid=' . $request->periode_uuid)
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
        $gaji = Gajian::with(['pegawai', 'jabatan', 'absensiPeriode'])
            ->where('uuid', $uuid)
            ->firstOrFail();

        // Ambil ulang detail absensi dari tabel absensi
        $periode = $gaji->absensiPeriode;
        if ($periode) {
            $detailAbsensi = $gaji->pegawai->absensi()
                ->whereBetween('tgl_absen', [$periode->tanggal_mulai, $periode->tanggal_selesai])
                ->with('jabatan')
                ->orderBy('tgl_absen', 'asc')
                ->get()
                ->map(function ($absen) {
                    $jabatan = $absen->jabatan;
                    $gajiHari = 0;
                    if ($jabatan) {
                        if ($jabatan->harian == 1) {
                            $gajiHari = $absen->grup_uuid == 'Pagi'
                                ? ($jabatan->gaji_pagi ?? 0)
                                : ($jabatan->gaji_malam ?? 0);
                        } elseif ($jabatan->harian == 2) {
                            $gajiHari = $absen->grup_uuid == 'Pagi'
                                ? ($absen->pencapaian ?? 0) * ($jabatan->gaji_pagi ?? 0)
                                : ($absen->pencapaian ?? 0) * ($jabatan->gaji_malam ?? 0);
                        }
                    }
                    return [
                        'tanggal'     => \Carbon\Carbon::parse($absen->tgl_absen)->format('d-m-Y'),
                        'jabatan'     => $jabatan?->jabatan ?? '-',
                        'grup_uuid'   => $absen->grup_uuid,
                        'gaji'        => $gajiHari,
                        'status'      => $absen->status,
                        'pencapaian'  => $absen->pencapaian ?? 0,
                    ];
                })->toArray();

            $gaji->detail_absensi = $detailAbsensi;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('gajian.slip', compact('gaji'))
            ->setPaper('A4', 'portrait');

        $nama = strtolower(trim($gaji->pegawai->nama));
        $tanggal = $gaji->created_at->translatedFormat('dmy');

        return $pdf->stream('slip-gaji_' . $nama . '_' . $tanggal . '.pdf');
    }
}
