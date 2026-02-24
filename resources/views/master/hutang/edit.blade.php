@extends('layouts.app')

@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Data Hutang</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('hutang.index')}}">Daftar Hutang</a></li>
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
                        Form Edit Hutang
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form method="POST" action="{{ route('hutang.update', $hutang->uuid) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pegawai <span class="text-danger">
                                        *</span></label>
                                <select name="pegawai_uuid"
                                    class="form-select @error('pegawai_uuid') is-invaluuid @enderror" required>
                                    <option disabled selected>Pilih Pegawai</option>
                                    @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->uuid }}"
                                        {{ old('pegawai_uuid', $hutang->pegawai_uuid) == $pegawai->uuid ? 'selected' : '' }}>
                                        {{ $pegawai->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('pegawai_uuid')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nominal Hutang <span class="text-danger">
                                        *</span></label>
                                <input type="number" name="nominal"
                                    class="form-control @error('nominal') is-invalid @enderror"
                                    value="{{ old('nominal', $hutang->nominal) }}" required>
                                @error('nominal')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                        {{ old('is_active', $hutang->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Aktif</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('hutang.index') }}" class="btn btn-md btn-danger">
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
    const nominal = document.getElementById('nominal');

    function formatCurrency(input) {
        let value = input.value.replace(/\D/g, '');
        if (value) input.value = new Intl.NumberFormat('id-ID').format(value);
    }

    nominal.addEventListener('input', () => formatCurrency(nominal));

    // Sebelum submit, hapus format currency agar tersimpan sebagai angka murni
    document.querySelector('form').addEventListener('submit', function() {
        nominal.value = nominal.value.replace(/\D/g, '');
    });
});
</script>
@endpush