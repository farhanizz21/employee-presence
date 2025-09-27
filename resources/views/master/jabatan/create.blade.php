@extends('layouts.app')
@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah jabatan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('jabatan.index')}}">Daftar jabatan</a></li>
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
                        Form Tambah jabatan
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form class="jabatan" method="post" action="{{ route('jabatan.store') }}">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Jabatan <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}"
                                    required>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sistem Gaji <span class="text-danger">
                                        *</span></label>
                                <select name="harian" class="form-select @error('harian') is-invalid @enderror"
                                    required>
                                    <option disabled selected>Pilih Sistem Gajian</option>
                                    <option value="1">Harian</option>
                                    <option value="2">Borongan</option>
                                    </option>
                                </select>
                                @error('harian')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gaji (min) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="gaji" id="gaji" class="form-control"
                                        value="{{ old('gaji') }}" required>
                                </div>
                                @error('gaji')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                        </div>
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('jabatan.index') }}" class="btn btn-md btn-danger">
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
    // Format input gaji as currency
    const gajiInput = document.getElementById('gaji');
    gajiInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-numeric characters
        e.target.value = new Intl.NumberFormat('id-ID').format(value); // Format as currency
    });
    document.querySelector('form').addEventListener('submit', function() {
        const gajiInput = document.getElementById('gaji');
        gajiInput.value = gajiInput.value.replace(/\D/g, '');
    });

});
</script>
@endpush