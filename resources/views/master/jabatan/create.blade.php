@extends('layouts.app')

@section('content')
<div class="app-content-header">
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">
        <h3 class="mb-0">Tambah Jabatan</h3>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-end">
          <li class="breadcrumb-item">
            <a href="{{ route('jabatan.index')}}">Daftar Jabatan</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
          <div class="card-title fw-bold text-primary">Form Tambah Jabatan</div>
        </div>

        <form class="jabatan" method="post" action="{{ route('jabatan.store') }}">
          @csrf
          <div class="card-body">
            <div class="form-group row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  Nama Jabatan <span class="text-danger">*</span>
                </label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan') }}" required>
                @error('jabatan')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">
                  Sistem Gaji <span class="text-danger">*</span>
                </label>
                <select name="harian" class="form-select @error('harian') is-invalid @enderror" required>
                  <option disabled {{ old('harian') ? '' : 'selected' }}>Pilih Sistem Gajian</option>
                  <option value="1" {{ old('harian') == 1 ? 'selected' : '' }}>Harian</option>
                  <option value="2" {{ old('harian') == 2 ? 'selected' : '' }}>Borongan</option>
                </select>
                @error('harian')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <div class="col-md-6 mb-3">
                <label class="form-label">
                  Gaji Pagi (min) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="text" name="gaji_pagi" id="gaji_pagi" class="form-control" value="{{ old('gaji_pagi') }}" required>
                </div>
                @error('gaji_pagi')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>

              <div class="col-md-6 mb-3">
                <label class="form-label">
                  Gaji Malam (min) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <span class="input-group-text">Rp</span>
                  <input type="text" name="gaji_malam" id="gaji_malam" class="form-control" value="{{ old('gaji_malam') }}" required>
                </div>
                @error('gaji_malam')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="card-footer">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('jabatan.index') }}" class="btn btn-md btn-danger">
              <i class="fa fa-times"></i> Batal
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Format input gaji sebagai currency
    const gajiPagi = document.getElementById('gaji_pagi');
    const gajiMalam = document.getElementById('gaji_malam');

    function formatCurrency(input) {
      let value = input.value.replace(/\D/g, '');
      if (value) input.value = new Intl.NumberFormat('id-ID').format(value);
    }

    gajiPagi.addEventListener('input', () => formatCurrency(gajiPagi));
    gajiMalam.addEventListener('input', () => formatCurrency(gajiMalam));

    // Sebelum submit, hapus format currency agar tersimpan sebagai angka murni
    document.querySelector('form').addEventListener('submit', function() {
      gajiPagi.value = gajiPagi.value.replace(/\D/g, '');
      gajiMalam.value = gajiMalam.value.replace(/\D/g, '');
    });
  });
</script>
@endpush
