<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #000;
        }

        h5, h6 {
            margin: 0;
            padding: 0;
        }

        h5 {
            font-size: 15px;
            font-weight: bold;
        }

        h6 {
            font-size: 13px;
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 6px;
        }

        p {
            margin: 2px 0;
            font-size: 11px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .table th, .table td {
            border: 1px solid #333;
            padding: 5px 8px;
            font-size: 10.5px;
        }

        .table th {
            background: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }

        .text-center { text-align: center; }
        .text-end { text-align: right; }
        .text-danger { color: red; }
        .text-muted { color: #777; }
        .fw-bold { font-weight: bold; }
        .table-primary { background: #d9eaf7; }

        hr {
            margin: 10px 0;
            border: none;
            border-top: 1px solid #aaa;
        }

        .mb-0 { margin-bottom: 0; }
        .mb-1 { margin-bottom: 5px; }
        .mb-3 { margin-bottom: 15px; }
        .mt-3 { margin-top: 15px; }

        .row { display: flex; justify-content: space-between; }
        .col-md-9 { width: 75%; }
    </style>
</head>
<body>

    <div class="row mb-3">
        <div class="col-md-9">
            <h5 class="mb-1">{{ $gaji->pegawai->nama }}</h5>
            <p><strong>Jabatan:</strong> {{ $gaji->jabatan->jabatan ?? '-' }}</p>
            <p><strong>Periode:</strong> Periode {{ date('j M Y', strtotime($gaji->absensiPeriode->tanggal_mulai)) }} - {{ date('j M Y', strtotime($gaji->absensiPeriode->tanggal_selesai)) }}</p>
        </div>
    </div>

    <hr>

    <!-- Rekap Kehadiran -->
    <h6>Rekap Kehadiran</h6>
    <table class="table">
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
    <h6>Rincian Gaji</h6>
    <table class="table">
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
                <td class="text-end text-danger">-{{ number_format($gaji->total_potongan ?? 0,0,',','.') }}</td>
            </tr>
            <tr class="table-primary fw-bold">
                <td>Total Gaji Diterima</td>
                <td class="text-end">{{ number_format($gaji->total_gaji,0,',','.') }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Rincian Absensi -->
    <h6 class="mt-3">Rincian Absensi Periode</h6>
    <table class="table">
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
