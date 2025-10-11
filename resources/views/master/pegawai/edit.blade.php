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
                <form method="POST" action="{{ route('pegawai.update', $pegawai->uuid) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ $pegawai->nama }}" required>
                                @error('nama')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Shift <span class="text-danger">*</span></label>
                                <select name="grup_uuid" class="form-select @error('grup_uuid') is-invalid @enderror" required>
                                    <option disabled {{ !$pegawai->grup_uuid ? 'selected' : '' }}>Pilih Shift</option>
                                    <option value="Pagi" {{ old('grup_uuid', $pegawai->grup_uuid) == 'Pagi' ? 'selected' : '' }}>Pagi</option>
                                    <option value="Malam" {{ old('grup_uuid', $pegawai->grup_uuid) == 'Malam' ? 'selected' : '' }}>Malam</option>
                                </select>
                                @error('grup')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select name="jabatan_uuid"
                                    class="form-select @error('jabatan_uuid') is-invalid @enderror" required>
                                    <option disabled {{ !$pegawai->jabatan ? 'selected' : '' }}>Pilih Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->uuid }}"
                                        {{ (old('jabatan', $pegawai->jabatan->uuid ?? '') == $jabatan->uuid) ? 'selected' : '' }}>
                                        {{ $jabatan->jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Grup <span class="text-danger">*</span></label>
                                <select name="grup_sb"
                                    class="form-select @error('grup_sb') is-invalid @enderror" required>
                                    <option disabled {{ !$pegawai->grup_sb ? 'selected' : '' }}>Pilih Grup</option>
                                    @foreach ($grups as $grup)
                                    <option value="{{ $grup->uuid }}"
                                        {{ (old('grup', $pegawai->grupSb->uuid ?? '') == $grup->uuid) ? 'selected' : '' }}>
                                        {{ $grup->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('grup_sb')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat"
                                    class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat', $pegawai->alamat) }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon <span class="text-danger">
                                        *</span></label>
                                <input type="number" name="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ $pegawai->telepon }}" required>
                                @error('telepon')
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
                                    class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $pegawai->keterangan) }}</textarea>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
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