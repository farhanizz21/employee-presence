@extends('layouts.app')
@section('content')

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah User</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('user.index')}}">Daftar User</a></li>
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
                        Form Tambah User
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
                <form method="post" action="{{ route('user.store') }}">
                    @csrf
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-label">Nama Pegawai (Opsional)</label>
                                <select name="pegawai_uuid" id="pegawai-select"
                                    class="form-control @error('pegawai_uuid') is-invalid @enderror">
                                    <option selected disabled>Pilih Pegawai</option>
                                    @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->uuid }}"
                                        {{ old('pegawai_uuid') == $pegawai->uuid ? 'selected' : '' }}>
                                        {{ $pegawai->nama }}
                                    </option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">
                                    Jika dipilih, user akan terhubung dengan pegawai ini.
                                </small>
                                @error('pegawai_uuid')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">username <span class="text-danger">
                                        *</span></label>
                                <input type="text" id="username" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username') }}" required>
                                @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label class="form-label">e-mail </label>
                                <input type="text" name="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required>
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role <span class="text-danger">
                                        *</span></label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option selected disabled>Pilih Role</option>
                                    <option value="1" {{ old('role')==1 ? 'selected' : '' }}>Admin</option>
                                    <option value="2" {{ old('role')==2 ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('user.index') }}" class="btn btn-md btn-danger">
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
$(document).ready(function() {

    $('#pegawai-select').select2({
        placeholder: "Cari atau pilih pegawai",
        theme: 'bootstrap-5',
        allowClear: true,
        width: '100%'
    });

    $('#pegawai-select').on('change', function() {
        var nama = $(this).find('option:selected').text().toLowerCase().trim();
        console.log(nama);
        if (!$(this).val()) {
            $('#username').val('');
            return;
        }
        var username = nama.replace(/\s+/g, '.');

        $('#username').val(username);
    });

});
</script>
@endpush