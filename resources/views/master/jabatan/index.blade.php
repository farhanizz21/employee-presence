@extends('layouts.app')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Master Jabatan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Master Jabatan</li>
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
                                    <h3 class="card-title mb-0">Data Master Jabatan</h3>
                                </div>
                                <div class="col-auto text-end">
                                    <a href="{{ route('jabatan.create')}}" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <form method="GET" action="{{ route('jabatan.index') }}"
                                class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Jabatan…"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('jabatan.index') }}" class="btn btn-warning">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    @php
                                    $currentSort = request('sort_by');
                                    $currentOrder = request('sort_order') == 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <thead>
                                        <tr>
                                            <th style="width: 2%;">#</th>
                                            <th>
                                                <a href="{{ route('jabatan.index', ['sort_by' => 'jabatan', 'sort_order' => $currentOrder] + request()->all()) }}"
                                                    class="text-light fw-bold">
                                                    Jabatan
                                                    @if(request('sort_by') == 'jabatan' && request('sort_order') ==
                                                    'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                    @elseif(request('sort_by') == 'jabatan' && request('sort_order') ==
                                                    'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th style="width: 10%;">
                                                <form method="GET" action="{{ route('jabatan.index') }}">
                                                    <select name="filter_sistem" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Sistem Gaji</option>
                                                        <option value="1"
                                                            {{ request('filter_sistem') == '1' ? 'selected' : '' }}>
                                                            Harian
                                                        </option>
                                                        <option value="2"
                                                            {{ request('filter_sistem') == '2' ? 'selected' : '' }}>
                                                            Borongan
                                                        </option>
                                                    </select>
                                                </form>
                                            </th>
                                            <th>Gaji</th>
                                            <th style="width: 10%;">Bonus</th>
                                            <th>Keterangan</th>
                                            <th style="width: 5%">
                                                Status
                                            </th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($jabatans as $jabatan)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-truncate">{{ $jabatan->jabatan }}
                                            </td>
                                            <td class="text-truncate">
                                                {{ $jabatan->harian_text }}
                                            </td>
                                            <td class="text-truncate">Rp
                                                {{ number_format($jabatan->gaji, 0, ',', '.') }}
                                            </td>
                                            <td class="text-truncate">
                                                {{ $jabatan->bonusPotongan->nama ?? '-' }}
                                            </td>
                                            <td class="text-truncate">
                                                {{ $jabatan->keterangan ?? '-'}}
                                            </td>
                                            <td>
                                                @if($jabatan->is_system)
                                                <span class="badge bg-secondary">Sistem</span>
                                                @else
                                                <span class="badge bg-primary">Bisa Edit</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- Tombol Edit --}}
                                                @if($jabatan->is_system)
                                                <a href="{{ route('jabatan.edit_system', $jabatan->uuid) }}"
                                                    class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Edit">
                                                    <i class="fas fa-coins text-white"></i>
                                                </a>
                                                @else
                                                <a href="{{ route('jabatan.edit', $jabatan->uuid) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit">
                                                    <i class="fas fa-edit text-white"></i>
                                                </a>
                                                @endif
                                                @if(!$jabatan->is_system)
                                                <form action="{{ route('jabatan.destroy', $jabatan->uuid) }}"
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
                                            <td colspan="8" class="text-center">No Data</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer clearfix">
                            <div class="float-end">
                                {{ $jabatans->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <!--end::Col-->
            </div>
            <!--end::Row-->
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