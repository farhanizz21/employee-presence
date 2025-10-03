@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Pegawai</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('pegawai.index')}}">Daftar Pegawai</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="app-content">
    <div class="container-fluid">
        <div class="col-12">
            <div class="card card-primary card-outline mb-4">
                <div class="card-header">
                    <div class="card-title fw-bold text-primary">Form Tambah Pegawai</div>
                </div>

                <!--begin::Form-->
                <form class="pegawai" method="post" action="{{ route('pegawai.store') }}">
                    @csrf
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ old('nama') }}" required>
                                @error('nama')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Grup <span class="text-danger">*</span></label>
                                <select name="grup" class="form-select @error('grup') is-invalid @enderror" required>
                                    <option disabled selected>Pilih Shift</option>
                                    <option value="Pagi">Pagi</option>
                                    <option value="Malam">Malam</option>
                                </select>
                                @error('grup')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select name="jabatan" class="form-select @error('jabatan') is-invalid @enderror" required>
                                    <option disabled selected>Pilih Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->uuid }}" {{ old('jabatan') == $jabatan->uuid ? 'selected' : '' }}>
                                        {{ $jabatan->jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="tel" name="telepon" class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ old('telepon') }}">
                                @error('telepon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror">{{ old('alamat') }}</textarea>
                                @error('alamat')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kolom isian untuk keterangan tambahan, jika ada.</div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pegawai.index') }}" class="btn btn-md btn-danger">
                            <i class="fa fa-times"></i> Batal
                        </a>
                    </div>
                </form>
                <!--end::Form-->

            </div>
        </div>
    </div>
</div>
@endsection