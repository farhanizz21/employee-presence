@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Absensi</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Absensi
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
                                    <h5 class="card-title mb-0">Data Absensi Pegawai</h5>
                                    <div class="col-auto text-end">
                                        <a href="{{ route('absensiUpdate.create') }}" class="btn btn-primary shadow-sm">
                                            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Body Card --}}
                        <div class="card-body">

                            {{-- 🔹 Filter Pencarian & Tambahan --}}
                            <form method="GET" action="{{ route('absensiUpdate.index') }}"
                                class="row g-2 align-items-center mb-4">

                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                                </div>

                                <div class="col-md-2">
                                    <select name="jabatan_uuid" class="form-select">
                                        <option value="">Semua Jabatan</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="grup_sb" class="form-select">
                                        <option value="">Semua Grup</option>
                                    </select>
                                </div>

                                {{-- 🔹 Filter Shift --}}
                                <div class="col-md-2">
                                    <select name="shift" class="form-select">
                                        <option value="">Semua Shift</option>
                                    </select>
                                </div>

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('absensiUpdate.index') }}" class="btn btn-warning">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>


                        </div>
                        <div class="card-footer clearfix">
                            <!-- pagination -->
                            <div class="float-end">
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

</div>
@endsection