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

        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data Periode Gajian</h5>
                <a href="{{ route('gajian.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Periode
                </a>
            </div>
            <div class="card-body pt-3 pb-2">
                <!-- PANDUAN (tidak terlalu dominan) -->
                <div class="mb-3">

                    <button class="btn btn-warning btn-sm font-weight-bold" type="button" data-toggle="collapse"
                        data-target="#panduanBox" aria-expanded="false">

                        <i class="fas fa-info-circle mr-1"></i> Panduan
                    </button>

                    <div id="panduanBox" class="collapse mt-2">

                        <div class="border rounded p-3 bg-light small">

                            <!-- ALUR -->
                            <div class="mb-2">
                                <strong>Alur Proses</strong><br>
                            </div>

                            <!-- DETAIL -->
                            <ul class="pl-3 mb-2">
                                <li>Status Draft: data belum di proses hitung → Klik Proses</li>
                                <li>Status Calculated : Klik review untuk tinjau gaji (di status ini masih bisa dilakukan penyesuaian. Cara ada di poin selanjutnya). Jika sudah sesuai, Klik Finalisasi</li>
                                <li>Status Final: kunci data & Cetak PDF</li>
                            </ul>

                            <!-- PENYESUAIAN -->
                            <div class="mb-2">
                                <strong>Penyesuaian (Perubahan Absensi, Bonus & Potongan)</strong>
                                <ul class="pl-3 mb-0">
                                    <li>Klik tombol 'Review'</li>
                                    <li>Untuk perubahan absen bisa klik 'Edit Absen' di pojok kiri bawah</li>
                                    <li>Untuk perubahan bonus dan potongan manual, klik icon 'Edit' di kolom aksi per pegawai</li>
                                    <li>Jika ada perubahan harus klik Recalculate agar dapat dihitung kembali</li>
                                    <li>Penyesuaian ini berlaku hanya sebelum status Final</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>#</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th style="width:250px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($periodes as $index => $periode)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>

                                <td>
                                    {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                                    -
                                    {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @if($periode->status == 'draft')
                                    <span class="badge bg-secondary">Draft</span>
                                    @elseif($periode->status == 'calculated')
                                    <span class="badge bg-warning text-dark">Calculated</span>
                                    @elseif($periode->status == 'final')
                                    <span class="badge bg-success">Final</span>
                                    @endif
                                </td>

                                <td class="text-center">

                                    {{-- DRAFT --}}
                                    @if($periode->status == 'draft')
                                    <a href="{{ route('gajian.proses', $periode->uuid) }}"
                                        class="btn btn-warning btn-sm"
                                        onclick="return confirm('Proses gajian sekarang?')">
                                        <i class="fas fa-play"></i> Proses
                                    </a>
                                    @endif

                                    {{-- CALCULATED --}}
                                    @if($periode->status == 'calculated')
                                    <a href="{{ route('gajian.show', $periode->uuid) }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-eye"></i> Review
                                    </a>
                                    <button class="btn btn-success btn-sm">
                                        <i class="fas fa-lock"></i> Finalisasi
                                    </button>
                                    @endif

                                    {{-- FINAL --}}
                                    @if($periode->status == 'final')
                                    <a href="{{ route('gajian.show', $periode->uuid) }}"
                                        class="btn btn-secondary btn-sm">
                                        <i class="fas fa-file-invoice"></i> Detail
                                    </a>
                                    @endif

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada periode gajian
                                </td>
                            </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>

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