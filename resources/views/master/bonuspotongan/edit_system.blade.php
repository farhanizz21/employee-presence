@extends('layouts.app')

@push('styles')
<style>
.select2-container--default .select2-selection--multiple {
    min-height: 38px;
    padding: 4px;
}

.select2-container--default .select2-selection__rendered {
    color: #000 !important;
}
</style>
@endpush

@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Master Bonus & Potongan</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('bonuspotongan.index')}}">Daftar Master Bonus &
                            Potongan</a>
                    </li>
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
                        Form Edit Master Bonus & Potongan
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form id="bonusForm" class="bonuspotongan" method="post"
                    action="{{ route('bonuspotongan.update_system',$bonuspotongan->uuid) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                <select class="form-select" disabled>
                                    <option value="1" {{ $bonuspotongan->jenis == 1 ? 'selected' : '' }}>Bonus</option>
                                    <option value="2" {{ $bonuspotongan->jenis == 2 ? 'selected' : '' }}>Potongan
                                    </option>
                                </select>
                                <input type="hidden" name="jenis" value="{{ $bonuspotongan->jenis }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ $bonuspotongan->nama }}" disabled>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nominal <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" name="nominal" id="nominal" class="form-control"
                                        value="{{ number_format($bonuspotongan->nominal, 0, ',', '.') }}" required>
                                </div>
                                @error('nominal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Berlaku untuk <span class="text-danger">*</span></label>

                                <select name="jabatan[]" id="selectJabatan"
                                    class="form-control select2 @error('jabatan') is-invalid @enderror" multiple>

                                    @foreach ($jabatans as $jabatan)
                                    <option value="{{ $jabatan->uuid }}"
                                        {{ collect(old('jabatan'))->contains($jabatan->uuid) ? 'selected' : '' }}>
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
                            <div class="col-md-12">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan"
                                    class="form-control @error('keterangan') is-invalid @enderror"
                                    disabled>{{ old('keterangan', $bonuspotongan->keterangan) }}</textarea>
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
    const nominalInput = document.getElementById('nominal');
    const form = document.getElementById('bonusForm');

    if (!form || !nominalInput) {
        console.error('FORM ATAU INPUT TIDAK DITEMUKAN');
        return;
    }

    nominalInput.addEventListener('input', function(e) {
        const raw = e.target.value.replace(/\D/g, '');
        e.target.value = new Intl.NumberFormat('id-ID').format(raw);
    });

    form.addEventListener('submit', function(e) {
        nominalInput.value = nominalInput.value.replace(/\D/g, '');
    });

    $('#selectJabatan').select2({
        placeholder: "Pilih jabatan (boleh lebih dari satu)",
        allowClear: true,
        width: '100%',
        // theme: 'default'
    });

});
</script>
@endpush