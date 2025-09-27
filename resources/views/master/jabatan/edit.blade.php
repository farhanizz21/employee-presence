@extends('layouts.app')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Jabatan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('jabatan.index')}}">Daftar Jabatan</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Edit
                    </li>
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
                    <div class="card-title fw-bold text-primary">
                        Form Edit Jabatan
                    </div>
                </div>

                <!--begin::Form-->
                <form method="post" action="{{ route('jabatan.update', $jabatan->uuid) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nama Jabatan <span class="text-danger">*</span></label>
                                <input type="text" name="jabatan"
                                    class="form-control @error('jabatan') is-invalid @enderror"
                                    value="{{ old('jabatan', $jabatan->jabatan) }}" required>
                                @error('jabatan')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sistem Gaji <span class="text-danger">*</span></label>
                                <select name="harian" class="form-select @error('harian') is-invalid @enderror" required>
                                    <option value="1" {{ old('harian', $jabatan->harian ?? 1) == 1 ? 'selected' : '' }}>Harian</option>
                                    <option value="2" {{ old('harian', $jabatan->harian ?? 2) == 2 ? 'selected' : '' }}>Borongan</option>
                                </select>
                                @error('harian')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Gaji <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="gaji" id="gaji"
                                        class="form-control @error('gaji') is-invalid @enderror"
                                        value="{{ old('gaji', number_format($jabatan->gaji, 0, ',', '.')) }}" required>
                                </div>
                                @error('gaji')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('jabatan.index') }}" class="btn btn-md btn-danger">
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