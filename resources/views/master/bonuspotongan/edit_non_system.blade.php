@extends('layouts.app')

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
                <form class="bonuspotongan" method="post"
                    action="{{ route('bonuspotongan.update_non_system',$bonuspotongan->uuid) }}">
                    @csrf
                    @method('PUT')
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                <select name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                                    <option value="1" {{ old('jenis', $bonuspotongan->jenis) == 1 ? 'selected' : '' }}>
                                        Bonus</option>
                                    <option value="2" {{ old('jenis', $bonuspotongan->jenis) == 2 ? 'selected' : '' }}>
                                        Potongan</option>
                                </select>
                                @error('jenis')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama <span class="text-danger">
                                        *</span></label>
                                <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror"
                                    value="{{ $bonuspotongan->nama }}" required>
                                @error('nama')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
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
                                <select name="jabatan[]"
                                    class="form-select select2 @error('jabatan') is-invalid @enderror" multiple
                                    required>
                                    @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->uuid }}" @if(collect(old('jabatan',
                                        is_array($bonuspotongan->jabatan) ? $bonuspotongan->jabatan :
                                        json_decode($bonuspotongan->jabatan, true)))
                                        ->contains($jabatan->uuid)) selected @endif>
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
                                    class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan', $bonuspotongan->keterangan) }}</textarea>
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
    $('.select2').select2({
        placeholder: "Pilih Jabatan",
        allowClear: true
    });
    const nominalInput = document.getElementById('nominal');
    nominalInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, ''); // Remove non-numeric characters
        e.target.value = new Intl.NumberFormat('id-ID').format(value); // Format as currency
    });
    document.querySelector('form').addEventListener('submit', function() {
        const nominalInput = document.getElementById('nominal');
        nominalInput.value = nominalInput.value.replace(/\D/g, '');
    });
});
</script>
@endpush