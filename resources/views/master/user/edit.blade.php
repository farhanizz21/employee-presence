@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit User</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('user.index')}}">Daftar User</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                    <div class="card-title fw-bold text-primary">Form Edit User</div>
                </div>

                <form method="POST" action="{{ route('user.update', $user->uuid) }}">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="form-group row">
                            @unless($user->username === 'admin')
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Pegawai (Opsional)</label>
                                <select name="pegawai_uuid" id="pegawai-select"
                                    class="form-control @error('pegawai_uuid') is-invalid @enderror">
                                    <option disabled {{ $user->pegawai_uuid ? '' : 'selected' }}>Pilih Pegawai</option>
                                    @foreach ($pegawais as $pegawai)
                                    <option value="{{ $pegawai->uuid }}" {{ old('pegawai_uuid', $user->pegawai_uuid) ==
                                        $pegawai->uuid ? 'selected' : '' }}>
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
                            @endunless
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" name="username"
                                    class="form-control @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" required>
                                @error('username')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">E-mail</label>
                                <input type="email" name="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-control @error('role') is-invalid @enderror">
                                    <option disabled selected>Pilih Role</option>
                                    <option value="1" {{ old('role', $user->role) == 1 ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="2" {{ old('role', $user->role) == 2 ? 'selected' : '' }}>User
                                    </option>
                                </select>
                                @error('role')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('user.index') }}" class="btn btn-danger">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        $('#pegawai-select').select2({
            placeholder: "Cari atau pilih pegawai",
            theme: 'bootstrap-5',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush