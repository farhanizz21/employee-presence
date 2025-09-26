@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Slip Gaji - {{ $gajian->pegawai->nama }}
        </div>
        <div class="card-body">
            <p><strong>Periode:</strong> {{ $gajian->periode_mulai }} s/d {{ $gajian->periode_selesai }}</p>
            <p><strong>Jabatan:</strong> {{ $gajian->pegawai->jabatan->jabatan }}</p>

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Nominal</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gajian->details as $detail)
                        <tr>
                            <td>{{ $detail->tanggal }}</td>
                            <td>{{ ucfirst(str_replace('_',' ',$detail->jenis)) }}</td>
                            <td class="text-end">{{ number_format($detail->nominal,0,',','.') }}</td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="text-end mt-3">
                Total Gaji: Rp {{ number_format($gajian->total_gaji,0,',','.') }}
            </h4>
        </div>
    </div>
</div>
@endsection
