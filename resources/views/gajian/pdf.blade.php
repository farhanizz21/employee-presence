<!DOCTYPE html>
<html>

<head>
    <style>
    body {
        font-family: sans-serif;
        font-size: 12px;
    }

    .title {
        text-align: center;
        font-size: 16px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td,
    th {
        padding: 6px;
        border: 1px solid #000;
    }
    </style>
</head>

@foreach($gajians as $gaji)

<body>
    <div class="title">
        <strong>SLIP GAJI</strong><br>
        {{ $gaji->pegawai->nama }}
    </div>

    <table>
        <tr>
            <th>Periode</th>
            <td>
                {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                -
                {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
            </td>
        </tr>

        <tr>
            <th>Hadir</th>
            <td>{{ $gaji->hadir }}</td>
        </tr>
        <tr>
            <th>Izin</th>
            <td>{{ $gaji->izin }}</td>
        </tr>
        <tr>
            <th>Alpha</th>
            <td>{{ $gaji->alpha }}</td>
        </tr>

        <tr>
            <th>Gaji Pokok</th>
            <td>Rp {{ number_format($gaji->gaji_pokok,0,',','.') }}</td>
        </tr>
        <tr>
            <th>Bonus</th>
            <td>Rp {{ number_format($gaji->bonus,0,',','.') }}</td>
        </tr>
        <tr>
            <th>Potongan</th>
            <td>Rp {{ number_format($gaji->potongan,0,',','.') }}</td>
        </tr>

        <tr>
            <th><strong>Gaji Bersih</strong></th>
            <td><strong>Rp {{ number_format($gaji->gaji_bersih,0,',','.') }}</strong></td>
        </tr>
    </table>

</body>
@endforeach

</html>