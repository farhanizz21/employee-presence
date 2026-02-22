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
                                <label class="form-label">Jabatan <span class="text-danger">*</span></label>
                                <select name="jabatan" id="jabatan-select"
                                    class="form-select @error('jabatan') is-invalid @enderror" required>
                                    <option disabled selected>Pilih Jabatan</option>
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->uuid }}"
                                        {{ old('jabatan') == $jabatan->uuid ? 'selected' : '' }}>
                                        {{ $jabatan->jabatan }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Grup <span class="text-danger">*</span></label>
                                <select name="grup" class="form-select @error('grup') is-invalid @enderror" required>
                                    <option disabled selected>Pilih Grup</option>
                                    @foreach ($grups as $grup)
                                    <option value="{{ $grup->uuid }}"
                                        {{ old('grup') == $grup->uuid ? 'selected' : '' }}>
                                        {{ $grup->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('grup')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="number" name="telepon"
                                    class="form-control @error('telepon') is-invalid @enderror"
                                    value="{{ old('telepon') }}">
                                @error('telepon')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Kolom isian untuk keterangan tambahan, jika ada.</div>
                            </div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
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

<style>
.select2-container--default .select2-selection--single {
    height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.select2-container--default.select2-container--focus .select2-selection--single {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.select2-container--default.select2-container--open .select2-selection--single {
    border-color: #80bdff;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    color: #495057;
    line-height: 1.5;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: calc(1.5em + 0.75rem + 2px);
    position: absolute;
    top: 1px;
    right: 1px;
    width: 35px;
}

.select2-container--default .select2-selection--single .select2-selection__arrow b {
    border-color: #495057 transparent transparent transparent;
}

.select2-container--default.select2-container--disabled .select2-selection--single {
    background-color: #e9ecef;
    opacity: 1;
}

.select2-container--default.select2-container--disabled .select2-selection__arrow {
    display: none;
}

.select2-container--default .select2-results__option--highlighted[aria-selected] {
    background-color: #007bff;
}

.select2-container--default .select2-results__option[aria-selected=true] {
    background-color: #e9ecef;
    color: #495057;
}

.select2-container--default .select2-search--dropdown .select2-search__field {
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}

.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #6c757d;
}
</style>

<script>
$(document).ready(function() {
    $('#jabatan-select').select2({
        placeholder: 'Pilih Jabatan',
        allowClear: true,
        width: '100%',
        theme: 'default'
    });
});
</script>
@endsection