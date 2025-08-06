@extends('layouts.app')
@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Bonus & Potongan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('bonuspotongan.index')}}">Daftar Bonus & Potongan</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Tambah
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
                        Form Tambah Bonus & Potongan
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form class="bonuspotongan" method="post" action="{{ route('bonuspotongan.store') }}">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis<span class="text-danger">
                                        *</span></label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option disabled selected>Pilih jenis</option>
                                    <option value="1" {{ old('jenis') == 1 ? 'selected' : '' }}>Bonus</option>
                                    <option value="2" {{ old('jenis') == 2 ? 'selected' : '' }}>Potongan</option>
                                </select>
                                @error('jenis')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama<span class="text-danger">
                                        *</span></label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
                                @error('nama')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nominal<span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="nominal" id="nominal" class="form-control"
                                        value="{{ old('nominal') }}" required>
                                </div>
                                @error('nominal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Berlaku untuk<span class="text-danger">*</span></label>
                                <select name="jabatan[]"
                                    class="form-select select2 @error('jabatan') is-invalid @enderror" multiple
                                    required>
                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->uuid }}"
                                        {{ (collect(old('jabatan'))->contains($jabatan->uuid)) ? 'selected' : '' }}>
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
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" class="form-control"
                                    value="{{ old('keterangan') }}">
                                @error('keterangan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                                <div class="form-text">
                                    Kolom isian untuk keterangan tambahan, jika ada.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('bonuspotongan.index') }}" class="btn btn-md btn-danger">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Format input nominal as currency
    const nominalInput = document.getElementById('nominal');
    nominalInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-numeric characters
        e.target.value = new Intl.NumberFormat('id-ID').format(value); // Format as currency
    });
    document.querySelector('form').addEventListener('submit', function() {
        const nominalInput = document.getElementById('nominal');
        nominalInput.value = nominalInput.value.replace(/\D/g, '');
    });

    $('.select2').select2({
        placeholder: "Pilih Jabatan",
        allowClear: true
    });


});
</script>
@endpush