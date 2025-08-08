@extends('layouts.app')
@section('content')

<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Edit Grup</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('grup.index')}}">Daftar Grup</a></li>
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
                        Form Edit Grup
                    </div>
                </div>

                <!--begin::Form-->
                <form class="grup" method="post" action="{{ route('grup.update', $grup->uuid) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Grup <span class="text-danger">*</span></label>
                                <input type="text" name="grup" class="form-control @error('grup') is-invalid @enderror"
                                    value="{{ old('grup', $grup->grup) }}" required>
                                @error('grup')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('grup.index') }}" class="btn btn-md btn-danger">
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