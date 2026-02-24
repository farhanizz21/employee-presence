@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Master Hutang</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Master Hutang
                    </li>
                </ol>
            </div>
        </div>
        <!--end::Row-->
    </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<!--begin::App Content-->
<div class="app-content">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-12">

                <!--begin::Col-->
                <div class="col-md-6 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <h3 class="card-title mb-0">Data Master Hutang</h3>
                                </div>
                                <div class="col-auto text-end">
                                    <a href="{{ route('hutang.create')}}" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{-- 🔹 Deskripsi Penggunaan --}}
                        <div class="card-body pt-3 pb-2">
                            <div class="alert alert-light border small mb-0">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informasi:</strong><br>
                                Master Hutang digunakan untuk mencatat dan mengelola cicilan hutang pegawai.
                                Jika status diatur sebagai <strong>Aktif</strong>, sistem akan otomatis melakukan
                                pemotongan gaji sesuai nominal yang tercantum saat proses penggajian, dan potongan
                                tersebut akan ditampilkan pada slip gaji.
                                Jika potongan ingin dihentikan sementara, ubah status menjadi
                                <strong>Non Aktif</strong>. Status dapat diaktifkan kembali sewaktu-waktu
                                apabila potongan akan diberlakukan kembali.
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('hutang.index') }}"
                                class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari Nama Pegawai" value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('hutang.index') }}" class="btn btn-warning">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover">
                                    @php
                                    $currentSort = request('sort_by');
                                    $currentOrder = request('sort_order') == 'asc' ? 'desc' : 'asc';
                                    @endphp
                                    <thead>
                                        <tr>
                                            <th style="width: 2%;">#</th>
                                            <th>Nama Pegawai </th>
                                            <th>
                                                <a href="{{ route('hutang.index', ['sort_by' => 'nominal', 'sort_order' => $currentOrder] + request()->all()) }}"
                                                    class="text-secondary fw-bold">
                                                    Nominal Hutang
                                                    @if(request('sort_by') == 'nominal' && request('sort_order') ==
                                                    'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                    @elseif(request('sort_by') == 'nominal' && request('sort_order') ==
                                                    'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th style="width: 15%">Status</th>
                                            <th style="width: 13%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($hutangs as $hutang)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $hutang->pegawai->nama }}</td>
                                            <td>{{ number_format($hutang->nominal, 2, ',', '.') }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <label class="form-check-label status-label"
                                                        for="status-{{ $hutang->uuid }}">
                                                        <span
                                                            class="badge {{ $hutang->is_active ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $hutang->is_active ? 'Aktif' : 'Non Aktif' }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route ('hutang.edit', $hutang->uuid) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit Data"><i class="fas fa-edit text-white"></i>
                                                </a>
                                                <form class="status-form"
                                                    action="{{ route('hutang.updateStatus', $hutang->uuid) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" data-bs-toggle="tooltip" title="Ubah Status"
                                                        class="btn btn-sm {{ $hutang->is_active ? 'btn-secondary' : 'btn-success' }}">
                                                        <i
                                                            class="fas {{ $hutang->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                    </button>
                                                </form>
                                                <form class="delete-form"
                                                    action="{{ route('hutang.destroy', $hutang->uuid) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger delete-btn"
                                                        data-bs-toggle="tooltip" title="Hapus Data">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No Data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                    </div> <!-- /.card -->

                </div> <!-- /.col -->
                <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->


    @endsection
    @push('styles')
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
    @endpush

    @push('scripts')
    <script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script>
    document.addEventListener('submit', function(e) {
        const form = e.target;

        // =========================
        // DELETE CONFIRMATION
        // =========================
        if (form.classList.contains('delete-form')) {
            e.preventDefault();

            Swal.fire({
                title: 'Hapus data hutang?',
                text: 'Data yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return;
        }

        // =========================
        // STATUS TOGGLE CONFIRMATION
        // =========================
        if (form.classList.contains('status-form')) {
            e.preventDefault();

            const button = form.querySelector('button');
            const isActive = button.classList.contains('btn-secondary');

            Swal.fire({
                title: isActive ? 'Nonaktifkan Hutang?' : 'Aktifkan Hutang?',
                text: isActive ?
                    'Hutang ini akan dinonaktifkan.' : 'Hutang ini akan diaktifkan kembali.',
                icon: isActive ? 'warning' : 'success',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#dc3545' : '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: isActive ?
                    'Ya, nonaktifkan!' : 'Ya, aktifkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return;
        }
    });
    </script>
    @endpush