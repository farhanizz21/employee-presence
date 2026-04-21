@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Gajian</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Gajian
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">

        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <h5>Detail Gaji</h5>
                    <small>
                        {{ $gaji->pegawai->nama }} |
                        {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                        -
                        {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
                    </small>
                </div>

                <a href="{{ route('gajian.pdf', [$periode->uuid, $gaji->pegawai_uuid]) }}"
                    class="btn btn-danger btn-sm">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>
            </div>

            <div class="card-body">

                <table class="table table-bordered">

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
                        <td class="text-success">
                            Rp {{ number_format($gaji->bonus,0,',','.') }}
                        </td>
                    </tr>

                    <tr>
                        <th>Potongan</th>
                        <td class="text-danger">
                            Rp {{ number_format($gaji->potongan,0,',','.') }}
                        </td>
                    </tr>

                    <tr class="fw-bold">
                        <th>Gaji Bersih</th>
                        <td>
                            Rp {{ number_format($gaji->gaji_bersih,0,',','.') }}
                        </td>
                    </tr>

                </table>

            </div>
        </div>

    </div>
    <!--end::App Content-->


    @endsection


    <style>
    .table thead th {

        background-color: #343a40 !important;
        color: #fff;

        text-align: center;
        vertical-align: middle;
    }
    </style>