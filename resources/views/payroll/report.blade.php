@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Laporan Payroll Bulanan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('payroll.report') }}" class="row g-3 mb-4">
                <div class="col-md-2">
                    <label>Bulan</label>
                    <input type="number" class="form-control" name="bulan" value="{{ $bulan }}" min="1" max="12">
                </div>
                <div class="col-md-2">
                    <label>Tahun</label>
                    <input type="number" class="form-control" name="tahun" value="{{ $tahun }}" min="2000" max="2100">
                </div>
                <div class="col-md-2 align-self-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Jumlah Hadir</th>
                        <th>Jumlah Lembur</th>
                        <th>Jumlah Telat</th>
                        <th>Jumlah Alpha</th>
                        <th>Total Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gajians as $gajian)
                        <tr>
                            <td>{{ $gajian->pegawai->nama }}</td>
                            <td>{{ $gajian->pegawai->jabatan->jabatan }}</td>
                            <td>{{ $gajian->jumlah_hadir }}</td>
                            <td>{{ $gajian->jumlah_lembur }}</td>
                            <td>{{ $gajian->jumlah_telat }}</td>
                            <td>{{ $gajian->jumlah_alpha }}</td>
                            <td class="text-end">Rp {{ number_format($gajian->total_gaji, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h4 class="text-end">
                Total Semua Gaji: Rp {{ number_format($gajians->sum('total_gaji'), 0, ',', '.') }}
            </h4>
        </div>
    </div>
</div>
@endsection
