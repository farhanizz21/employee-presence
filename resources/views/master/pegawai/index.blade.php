@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Master Pegawai</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Master Pegawai
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
                                    <h3 class="card-title mb-0">Data Master Pegawai</h3>
                                </div>
                                <div class="col-auto text-end">
                                    <a href="{{ route('pegawai.create')}}" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('pegawai.index') }}"
                                class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pegawai…"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('pegawai.index') }}" class="btn btn-warning">
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
                                                <a href="{{ route('pegawai.index', ['sort_by' => 'nama', 'sort_order' => $currentOrder] + request()->all()) }}"
                                                    class="text-secondary fw-bold">
                                                    Nama
                                                    @if(request('sort_by') == 'nama' && request('sort_order') == 'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                    @elseif(request('sort_by') == 'nama' && request('sort_order') ==
                                                    'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>
                                                <form method="GET" action="{{ route('pegawai.index') }}">
                                                    <select name="filter_jabatan" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Jabatan</option>
                                                        @foreach ($jabatans as $jabatan)
                                                        <option value="{{ $jabatan->uuid }}" {{
                                                            request('filter_jabatan')==$jabatan->uuid ? 'selected' : ''
                                                            }}>
                                                            {{ $jabatan->jabatan }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                                    <input type="hidden" name="sort_by"
                                                        value="{{ request('sort_by') }}">
                                                    <input type="hidden" name="sort_order"
                                                        value="{{ request('sort_order') }}">
                                                </form>
                                            </th>
                                            <th>
                                                <form method="GET" action="{{ route('pegawai.index') }}">
                                                    <select name="filter_grup" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Grup</option>
                                                        @foreach ($grups as $grup)
                                                        <option value="{{ $grup->uuid }}" {{
                                                            request('filter_grup')==$grup->uuid ? 'selected' : '' }}>
                                                            {{ $grup->grup }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                                    <input type="hidden" name="sort_by"
                                                        value="{{ request('sort_by') }}">
                                                    <input type="hidden" name="sort_order"
                                                        value="{{ request('sort_order') }}">
                                                </form>
                                            </th>
                                            <th>Telepon</th>
                                            <th>Ket</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pegawais as $pegawai)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                {{ $pegawai->nama }}
                                            </td>
                                            <td class="text-truncate">{{ $pegawai->jabatan->jabatan }}</td>
                                            <td class="text-truncate">{{ $pegawai->grup->grup }}</td>
                                            <td class="text-truncate">{{ $pegawai->telepon }}</td>
                                            <td class="text-truncate">{{ $pegawai->keterangan }}</td>
                                            <td>
                                                <a href="{{route ('pegawai.edit', $pegawai->uuid) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit Data"><i class="fas fa-edit text-white"></i>
                                                </a>
                                                <form action="{{ route('pegawai.destroy', $pegawai->uuid) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="tooltip" title="Hapus Data">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
                                {{ $pegawais->withQueryString()->links('pagination::bootstrap-4') }}
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