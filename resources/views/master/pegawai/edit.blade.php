@extends('layouts.app')

@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Data Pegawai</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('pegawai.index')}}">Daftar Pegawai</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <!--begin::App Content-->
        <div class="col-12">
            <!--begin::Quick Example-->
            <div class="card card-primary card-outline mb-4">
                <!--begin::Header-->
                <div class="card-header">
                    <div class="card-title fw-bold text-primary">
                        Form Edit Pegawai
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form class="pegawai" method="post" action="{{ route('pegawai.update',$Pegawai->uuid) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-label">Nama <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ $Pegawai->nama }}" required>
                                @error('nama')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Golongan <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="golongan"
                                    class="form-control @error('golongan') is-invalid @enderror"
                                    value="{{ old('golongan', $Pegawai->golongan_uuid) }}" required>
                                @error('golongan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-label">Jabatan <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="jabatan"
                                    class="form-control @error('jabatan') is-invalid @enderror"
                                    value="{{ $Pegawai->jabatan_uuid }}" required>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telepon <span class="text-danger">
                                        *</span></label>
                                <input type="number" name="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ $Pegawai->telepon }}" required>
                                @error('telepon')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                    value="{{ $Pegawai->alamat }}"></textarea>
                                @error('alamat')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    value="{{ $Pegawai->keterangan }}"></textarea>
                            </div>
                            <div class="form-text">
                                Kolom isian untuk keterangan tambahan, jika ada.
                            </div>
                            @error('keterangan')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                    <!--end::Footer-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Quick Example-->
        </div>
        <!--end::Col-->
    </div>
</div>

@endsection