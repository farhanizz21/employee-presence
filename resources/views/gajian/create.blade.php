@extends('layouts.app')
@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah gajian</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('gajian.index')}}">Daftar gajian</a></li>
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

        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0">Tambah Periode Gajian</h5>
            </div>

            <div class="card-body">
                <form action="{{ route('gajian.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Periode Gajian</label>

                        <input type="text" id="tanggal_range"
                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                            placeholder="Pilih periode" readonly style="background:#fff; cursor:pointer;">

                        {{-- hidden input --}}
                        <input type="hidden" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                        <input type="hidden" name="tanggal_selesai" id="tanggal_selesai"
                            value="{{ old('tanggal_selesai') }}">

                        @error('tanggal_mulai')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('gajian.index') }}" class="btn btn-secondary me-2">
                            Batal
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Simpan (Draft)
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {

    $('#tanggal_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD'
        }
    });

    $('#tanggal_range').on('apply.daterangepicker', function(ev, picker) {
        $('#tanggal_range').val(
            picker.startDate.format('YYYY-MM-DD') + ' - ' +
            picker.endDate.format('YYYY-MM-DD')
        );

        $('#tanggal_mulai').val(picker.startDate.format('YYYY-MM-DD'));
        $('#tanggal_selesai').val(picker.endDate.format('YYYY-MM-DD'));
    });

});
</script>
@endsection