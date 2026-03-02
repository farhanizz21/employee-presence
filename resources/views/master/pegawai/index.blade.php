@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Master Pegawai</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Master Pegawai
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
                                    <h3 class="card-title mb-0">Data Master Pegawai</h3>
                                </div>
                                <div class="col-auto text-end">
                                    <a href="{{ route('pegawai.create')}}" class="btn btn-primary shadow-sm">
                                        <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('pegawai.index') }}"
                                class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari pegawai…"
                                        value="{{ request('search') }}">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('pegawai.index') }}" class="btn btn-warning">
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
                                            <th style="width: 2%">#</th>
                                            <th>
                                                <a href="{{ route('pegawai.index', ['sort_by' => 'nama', 'sort_order' => $currentOrder] + request()->all()) }}"
                                                    class="text-secondary fw-bold">
                                                    Nama
                                                    @if(request('sort_by') == 'nama' && request('sort_order') == 'asc')
                                                    <i class="fas fa-sort-up"></i>
                                                    @elseif(request('sort_by') == 'nama' && request('sort_order') ==
                                                    'desc')
                                                    <i class="fas fa-sort-down"></i>
                                                    @else
                                                    <i class="fas fa-sort"></i>
                                                    @endif
                                                </a>
                                            </th>
                                            <th style="width: 15%">
                                                <form method="GET" action="{{ route('pegawai.index') }}">
                                                    <select name="filter_jabatan" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Jabatan</option>
                                                        @foreach ($jabatans as $jabatan)
                                                        <option value="{{ $jabatan->uuid }}" {{
                                                            request('filter_jabatan')==$jabatan->uuid ? 'selected' : ''
                                                            }}>
                                                            {{ $jabatan->jabatan }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                                    <input type="hidden" name="filter_grup"
                                                        value="{{ request('filter_grup') }}">
                                                    <input type="hidden" name="sort_by"
                                                        value="{{ request('sort_by') }}">
                                                    <input type="hidden" name="sort_order"
                                                        value="{{ request('sort_order') }}">
                                                </form>
                                            </th>
                                            <th>
                                                <form method="GET" action="{{ route('pegawai.index') }}">
                                                    <select name="filter_shift" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Shift</option>
                                                        <option value="1"
                                                            {{ request('filter_shift')=='1' ? 'selected' : '' }}>Pagi
                                                        </option>
                                                        <option value="2"
                                                            {{ request('filter_shift')=='2' ? 'selected' : '' }}>
                                                            Malam</option>
                                                    </select>

                                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                                    <input type="hidden" name="sort_by"
                                                        value="{{ request('sort_by') }}">
                                                    <input type="hidden" name="sort_order"
                                                        value="{{ request('sort_order') }}">
                                                </form>
                                            </th>
                                            <th style="width: 15%">
                                                <form method="GET" action="{{ route('pegawai.index') }}">
                                                    <select name="filter_grup" class="form-select form-select-sm"
                                                        onchange="this.form.submit()">
                                                        <option value="">Semua Grup</option>
                                                        @foreach ($grups as $grup)
                                                        <option value="{{ $grup->uuid }}" {{
                                                            request('filter_grup')==$grup->uuid ? 'selected' : ''
                                                            }}>
                                                            {{ $grup->nama }}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                                    <input type="hidden" name="filter_jabatan"
                                                        value="{{ request('filter_jabatan') }}">
                                                    <input type="hidden" name="sort_by"
                                                        value="{{ request('sort_by') }}">
                                                    <input type="hidden" name="sort_order"
                                                        value="{{ request('sort_order') }}">
                                                </form>
                                            </th>
                                            <th>Telepon</th>
                                            <th>Ket</th>
                                            <th style="width: 2%">Status</th>
                                            <th style="width: 13%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pegawais as $pegawai)
                                        <tr class="align-middle">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-truncate">
                                                {{ $pegawai->nama }}
                                            </td>
                                            <td class="text-truncate">{{ $pegawai->jabatan->jabatan }}</td>
                                            <td class="text-truncate">{{ $pegawai->shift_label }}</td>
                                            <td class="text-truncate">{{ $pegawai->grup->nama }}</td>
                                            <td class="text-truncate">{{ $pegawai->telepon }}</td>
                                            <td class="text-truncate">{{ $pegawai->keterangan ?? '-' }}</td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <label class="form-check-label status-label"
                                                        for="status-{{ $pegawai->uuid }}">
                                                        <span
                                                            class="badge {{ $pegawai->status ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $pegawai->status ? 'Aktif' : 'Non Aktif' }}
                                                        </span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{route ('pegawai.edit', $pegawai->uuid) }}"
                                                    class="btn btn-sm btn-warning" data-bs-toggle="tooltip"
                                                    title="Edit Data"><i class="fas fa-edit text-white"></i>
                                                </a>
                                                <form class="status-form"
                                                    action="{{ route('pegawai.updateStatus', $pegawai->uuid) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" data-bs-toggle="tooltip" title="Ubah Status"
                                                        class="btn btn-sm {{ $pegawai->status ? 'btn-secondary' : 'btn-success' }}">
                                                        <i
                                                            class="fas {{ $pegawai->status ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                    </button>
                                                </form>
                                                <form class="delete-form"
                                                    action="{{ route('pegawai.destroy', $pegawai->uuid) }}"
                                                    method="POST" style="display:inline;">
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
                                            <td colspan="8" class="text-center">No Data
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                        <div class="card-footer clearfix">

                            <div class="float-end">
                                {{ $pegawais->withQueryString()->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
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
                title: 'Hapus data pegawai?',
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
                title: isActive ? 'Nonaktifkan Pegawai?' : 'Aktifkan Pegawai?',
                text: isActive ?
                    'Pegawai ini akan dinonaktifkan.' : 'Pegawai ini akan diaktifkan kembali.',
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