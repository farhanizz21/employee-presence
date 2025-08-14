<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    h2 {
        text-align: center;
        margin-bottom: 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
    }

    th {
        width: 30%;
        /* batasi lebar kolom label */
        font-weight: bold;
        background: #f2f2f2;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .text-left {
        text-align: left;
    }
    </style>
</head>

<body>
    <h2>Slip Gaji Pegawai</h2>
    <p style="text-align:center;">Periode: {{ \Carbon\Carbon::parse($gaji->periode_mulai)->translatedFormat('d F Y') }}
        s/d {{ \Carbon\Carbon::parse($gaji->periode_selesai)->translatedFormat('d F Y') }}</p>

    <table>
        <tr>
            <th class="text-left">Nama Pegawai</th>
            <td>{{ $gaji->pegawai->nama }}</td>
        </tr>
        <tr>
            <th class="text-left">Jabatan</th>
            <td>{{ $gaji->jabatan->jabatan }}</td>
        </tr>
    </table>

    <h4>Rincian Gaji</h4>
    <table>
        <tr>
            <th>Deskripsi</th>
            <th class="text-right">Nominal (Rp)</th>
        </tr>
        <tr>
            <td>Gaji Pokok</td>
            <td class="text-right">{{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bonus Lembur</td>
            <td class="text-right">{{ number_format($gaji->bonus_lembur, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Bonus Kehadiran</td>
            <td class="text-right">{{ number_format($gaji->bonus_kehadiran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Potongan</td>
            <td class="text-right text-danger">-{{ number_format($gaji->total_potongan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Gaji</th>
            <th class="text-right">{{ number_format($gaji->total_gaji, 0, ',', '.') }}</th>
        </tr>
    </table>

    <p style="margin-top: 40px;">Keterangan: {{ $gaji->keterangan ?? '-' }}</p>

    <p style="text-align:right; margin-top:60px;">
        <em>{{ now()->translatedFormat('d F Y') }}</em><br>
        <strong>Management</strong>
    </p>
</body>

</html>