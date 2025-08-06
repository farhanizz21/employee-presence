@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Master Grup</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Master Grup
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
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center text-dark" role="alert">
                    {{ session('success') }}
                </div>
                @endif
                <!--begin::Col-->
                <div class="col-md-6 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <h3 class="card-title mb-0">Data Master Grup</h3>
                                </div>
                                <div class="col-auto text-end">
                                    <a href="{{ route('grup.create')}}" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('grup.index') }}"
                                class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari Grup…"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('grup.index') }}" class="btn btn-warning">
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
                                                <a href="{{ route('grup.index', ['sort_by' => 'grup', 'sort_order' => $currentOrder] + request()->all()) }}"
                                                    class="text-secondary fw-bold">
                                                    Grup
                                                    @if(request('sort_by') == 'grup' && request('sort_order') ==
                                                    'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                    @elseif(request('sort_by') == 'grup' && request('sort_order') ==
                                                    'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($grups as $grup)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-truncate" style="max-width: 200px;">
                                                {{ $grup->grup }}
                                            </td>
                                            <td>
                                                <a href="{{route ('grup.edit', $grup->uuid) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit Data"><i class="fas fa-edit text-white"></i>
                                                </a>
                                                <form action="{{ route('grup.destroy', $grup->uuid) }}" method="POST"
                                                    style="display:inline;">
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
                                {{ $grups->withQueryString()->links('pagination::bootstrap-4') }}
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