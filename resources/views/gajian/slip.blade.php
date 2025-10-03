<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .table th, .table td { border: 1px solid #333; padding: 5px; }
        .table th { background: #f2f2f2; }
        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-danger { color: red; }
        .fw-bold { font-weight: bold; }
        .table-primary { background: #d9eaf7; }
        .mb-3 { margin-bottom: 15px; }
        .mb-1 { margin-bottom: 5px; }
        hr { margin: 10px 0; border: none; border-top: 1px solid #ccc; }
    </style>
</head>
<body>

    <div class="row mb-3">
        <div class="col-md-9">
            <h5 class="mb-1">{{ $gaji->pegawai->nama }}</h5>
            <p class="mb-0">
                <strong>Jabatan : </strong>
                {{ $gaji->jabatan->jabatan ?? '-' }}
            </p>
            <p class="mb-0">
                <strong>Periode : </strong>
                {{ $gaji->absensiPeriode->nama_periode ?? '-' }}
            </p>
        </div>
    </div>

    <hr>

    <!-- Rekap Kehadiran -->
    <h6 class="fw-bold mb-2">Rekap Kehadiran</h6>
    <table class="table table-sm table-bordered mb-3">
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-center">Jumlah Hari</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Hadir</td>
                <td class="text-center">{{ $gaji->jumlah_hadir }}</td>
            </tr>
            <tr>
                <td>Lembur</td>
                <td class="text-center">{{ $gaji->jumlah_lembur }}</td>
            </tr>
            <tr>
                <td>Terlambat</td>
                <td class="text-center">{{ $gaji->jumlah_telat }}</td>
            </tr>
            <tr>
                <td>Alpha</td>
                <td class="text-center">{{ $gaji->jumlah_alpha }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Rincian Gaji -->
    <h6 class="fw-bold mb-2">Rincian Gaji</h6>
    <table class="table table-sm table-bordered mb-3">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th class="text-end">Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td class="text-end">{{ number_format($gaji->gaji_pokok,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Bonus Lembur</td>
                <td class="text-end">{{ number_format($gaji->bonus_lembur ?? 0,0,',','.') }}</td>
            </tr>
            <tr>
                <td>Bonus Kehadiran</td>
                <td class="text-end">{{ number_format($gaji->bonus_kehadiran ?? 0,0,',','.') }}</td>
            </tr>
            <tr>
                <td class="text-danger">Potongan Terlambat</td>
                <td class="text-end text-danger">
                    -{{ number_format($gaji->total_potongan ?? 0,0,',','.') }}
                </td>
            </tr>
            <tr class="table-primary fw-bold">
                <td>Total Gaji Diterima</td>
                <td class="text-end">{{ number_format($gaji->total_gaji,0,',','.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Rincian Absensi -->
    <h6 class="mt-3">Rincian Absensi Periode</h6>
    <table class="table table-sm table-bordered align-middle">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jabatan</th>
                <th>Shift</th>
                <th>Gaji Harian</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($gaji->detail_absensi) && is_array($gaji->detail_absensi))
                @foreach($gaji->detail_absensi as $detail)
                    <tr>
                        <td>{{ $detail['tanggal'] }}</td>
                        <td>{{ $detail['jabatan'] }}</td>
                        <td>{{ $detail['grup_uuid'] }}</td>
                        <td class="text-end">{{ number_format($detail['gaji'],0,',','.') }}</td>
                        <td>{{ $detail['status'] }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center text-muted">Tidak ada data</td>
                </tr>
            @endif
        </tbody>
    </table>

</body>
</html>
