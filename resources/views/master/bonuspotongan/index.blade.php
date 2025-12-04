@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Master Bonus & Potongan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Master Bonus & Potongan
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
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">

                <!--begin::Col-->
                <div class="col-md-6 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <h3 class="card-title mb-0">Data Master Bonus & Potongan</h3>
                                </div>
                                <div class="col-auto text-end">
                                    <a href="{{ route('bonuspotongan.create')}}" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('bonuspotongan.index') }}"
                                class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari Bonus & Potongan…" value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('bonuspotongan.index') }}" class="btn btn-warning">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    @php
                                    $currentSort = request('sort_by');
                                    $currentOrder = request('sort_order') == 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <thead>
                                        <tr>
                                            <th style="width: 10px">#</th>
                                            <th>
                                                <a href="{{ route('bonuspotongan.index', ['sort_by' => 'nama', 'sort_order' => $currentOrder] + request()->all()) }}"
                                                    class="text-light fw-bold">
                                                    Nama
                                                    @if(request('sort_by') == 'nama' && request('sort_order')
                                                    ==
                                                    'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                    @elseif(request('sort_by') == 'nama' &&
                                                    request('sort_order') ==
                                                    'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                <form method="GET" action="{{ route('bonuspotongan.index') }}">
                                                    <select name="jenis" class="form-select form-select-sm mt-1"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Jenis</option>
                                                        <option value="1" {{ request('jenis')==1 ? 'selected' : '' }}>
                                                            Bonus</option>
                                                        <option value="2" {{ request('jenis')==2 ? 'selected' : '' }}>
                                                            Potongan</option>
                                                    </select>
                                                </form>
                                            </th>
                                            <th>
                                                Nominal
                                            </th>
                                            <th>
                                                Keterangan
                                            </th>
                                            <th style="width: 5%">
                                                Status
                                            </th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bonuspotongans as $bonuspotongan)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                {{ $bonuspotongan->nama }}
                                            </td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                {{ $bonuspotongan->jenis_label }}
                                            </td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                Rp {{ number_format($bonuspotongan->nominal, 0, ',', '.') }}
                                            </td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                {{ $bonuspotongan->keterangan }}
                                            </td>
                                            <td>
                                                @if($bonuspotongan->is_system)
                                                <span class="badge bg-secondary">Sistem</span>
                                                @else
                                                <span class="badge bg-{{ $bonuspotongan->status_class }}">
                                                    {{ $bonuspotongan->status_label }}
                                                </span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- Tombol Edit --}}
                                                @if($bonuspotongan->is_system)
                                                <a href="{{ route('bonuspotongan.edit_system', $bonuspotongan->uuid) }}"
                                                    class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-coins text-white"></i>
                                                </a>
                                                @else
                                                <a href="{{ route('bonuspotongan.edit_non_system', $bonuspotongan->uuid) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit">
                                                    <i class="fas fa-edit text-white"></i>
                                                </a>
                                                @endif

                                                {{-- Tombol Hapus hanya untuk data bukan sistem --}}
                                                @if(!$bonuspotongan->is_system)
                                                <form
                                                    action="{{ route('bonuspotongan.destroy', $bonuspotongan->uuid) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="tooltip" title="Hapus Data">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan=" 7" class="text-center">No Data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                        <div class="card-footer clearfix">

                            <div class="float-end">
                                {{ $bonuspotongans->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div> <!-- /.card -->

                </div> <!-- /.col -->
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
        </div>
        <!--end::Container-->
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