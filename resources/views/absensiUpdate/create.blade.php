@extends('layouts.app')
@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Absensi</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('absensiUpdate.index')}}">Absensi</a>
                    </li>
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
            <div class="col-12">
                <div class="col-md-6 col-lg-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <h5 class="card-title mb-0">Tambah Data Absensi</h5>
                                </div>
                            </div>
                        </div>
                        {{-- Body Card --}}
                        <div class="card-body">
                            <form method="GET" action="{{ route('absensiUpdate.create') }}"
                                class="row mb-3 align-items-end g-2">
                                <div class="p-3 mb-3 rounded bg-light border">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <div>
                                            <small class="text-muted d-block">Tanggal Absensi</small>

                                            <div class="d-flex align-items-center gap-2">
                                                <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="changeDate(-1)">
                                                    <i class="fas fa-chevron-left"></i>
                                                </button>

                                                <input type="date" name="tanggal_absen" id="tanggal_absen"
                                                    class="form-control form-control-sm"
                                                    value="{{ request('tanggal_absen') ?? date('Y-m-d') }}"
                                                    onchange="this.form.submit()">

                                                <button class="btn btn-outline-secondary btn-sm"
                                                    onclick="changeDate(1)">
                                                    <i class="fas fa-chevron-right"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                            <small class="text-muted d-block">Per Page</small>
                                            <select name="per_page" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                <option value="10">10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <!-- 🔹 BARIS 2 -->
                                <div class="row g-2 align-items-end">

                                    <!-- SEARCH -->
                                    <div class="col-md-3">
                                        <label class="form-label">Cari Pegawai</label>
                                        <input type="text" name="search" class="form-control"
                                            value="{{ request('search') }}" placeholder="Nama pegawai...">
                                    </div>

                                    <!-- FILTER JABATAN -->
                                    <div class="col-md-2">
                                        <label class="form-label">Jabatan</label>
                                        <select name="filter_jabatan" class="form-select">
                                            <option value="">Semua</option>
                                            @foreach ($jabatans as $jabatan)
                                            <option value="{{ $jabatan->uuid }}"
                                                {{ request('filter_jabatan')==$jabatan->uuid ? 'selected' : '' }}>
                                                {{ $jabatan->jabatan }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- FILTER GRUP -->
                                    <div class="col-md-2">
                                        <label class="form-label">Grup</label>
                                        <select name="filter_grup" class="form-select">
                                            <option value="">Semua</option>
                                            @foreach ($grups as $grup)
                                            <option value="{{ $grup->uuid }}"
                                                {{ request('filter_grup')==$grup->uuid ? 'selected' : '' }}>
                                                {{ $grup->nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- FILTER SHIFT -->
                                    <div class="col-md-2">
                                        <label class="form-label">Shift</label>
                                        <select name="filter_shift" class="form-select">
                                            <option value="">Semua</option>
                                            <option value="1" {{ request('filter_shift')=='1' ? 'selected' : '' }}>
                                                Pagi
                                            </option>
                                            <option value="2" {{ request('filter_shift')=='2' ? 'selected' : '' }}>
                                                Malam
                                            </option>
                                        </select>
                                    </div>

                                    <!-- BUTTON -->
                                    <div class="col-md-1 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-search"></i>
                                        </button>

                                        <a href="{{ route('absensiUpdate.create') }}" class="btn btn-secondary">
                                            <i class="fas fa-redo"></i>
                                        </a>
                                    </div>
                                </div>
                            </form>
                            <form action="{{ route('absensiUpdate.store') }}" method="POST">
                                @csrf

                                <input type="hidden" name="tanggal_absen"
                                    value="{{ request('tanggal_absen') ?? date('Y-m-d') }}">

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
                                                    Nama
                                                </th>
                                                <th style="width: 15%">
                                                    Jabatan
                                                </th>
                                                <th style="width: 15%">
                                                    Grup
                                                </th>
                                                <th>
                                                    Shift
                                                </th>
                                                <th>Status</th>
                                                <th>Pencapaian</th>
                                                <th style="width: 13%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pegawais as $pegawai)
                                            @php
                                            $absensi = $existingAbsensi->get($pegawai->uuid);
                                            $status = $absensi ? $absensi->status : 1;
                                            $pencapaian = $absensi ? $absensi->pencapaian : '';
                                            @endphp
                                            <tr class="align-middle">
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-truncate">
                                                    {{ $pegawai->nama }}
                                                </td>
                                                <td class="text-truncate">{{ $pegawai->jabatan->jabatan }}</td>
                                                <td class="text-truncate">{{ $pegawai->grup->nama }}</td>
                                                <td class="text-truncate">{{ $pegawai->shift_label }}
                                                    <input type="hidden" name="data[{{ $pegawai->uuid }}][shift]"
                                                        value="{{ $pegawai->shift }}">
                                                </td>
                                                <td>
                                                    <div class="btn-group btn-group-sm status-group">
                                                        <button type="button"
                                                            class="btn {{ $status == 1 ? 'btn-success active' : 'btn-outline-success' }}"
                                                            data-value="1">
                                                            Masuk
                                                        </button>
                                                        <button type="button"
                                                            class="btn {{ $status == 2 ? 'btn-warning active' : 'btn-outline-warning' }}"
                                                            data-value="2">
                                                            Izin
                                                        </button>
                                                        <button type="button"
                                                            class="btn {{ $status == 3 ? 'btn-danger active' : 'btn-outline-danger' }}"
                                                            data-value="3">
                                                            Alpha
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="data[{{ $pegawai->uuid }}][status]"
                                                        class="status-input" value="{{ $status }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="data[{{ $pegawai->uuid }}][pencapaian]"
                                                        class="form-control" placeholder="Pencapaian"
                                                        value="{{ $pencapaian }}">
                                                </td>
                                                <td>
                                                    <!-- <button type="button" class="btn btn-sm btn-warning edit-btn"
                                                    title="Ubah Data">
                                                    <i class="fas fa-edit text-white"></i>
                                                </button> -->
                                                    <button type="button" class="btn btn-sm btn-primary longshift-btn"
                                                        title="Tambah Long Shift" data-pegawai="{{ $pegawai->uuid }}"
                                                        data-nama="{{ $pegawai->nama }}"
                                                        data-shift="{{ $pegawai->shift }}">
                                                        <i class="fas fa-clock"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            {{-- 🔥 AUTO RENDER LONG SHIFT JIKA SUDAH ADA DATA --}}
                                            @php
                                            $longShift = $existingAbsensi->get($pegawai->uuid . '_long');
                                            @endphp

                                            @if($longShift)
                                            @php
                                            $lsStatus = $longShift->status;
                                            $lsPencapaian = $longShift->pencapaian;
                                            $lsShift = $longShift->shift;
                                            $lsShiftLabel = $lsShift == 1 ? 'Pagi' : 'Malam';
                                            @endphp
                                            <tr class="align-middle longshift-row"
                                                data-pegawai-uuid="{{ $pegawai->uuid }}">
                                                <td>#</td>
                                                <td class="text-truncate">
                                                    <span class="badge bg-success ms-1">Long Shift</span>
                                                </td>
                                                <td></td>
                                                <td></td>
                                                <td>{{ $lsShiftLabel }}</td>
                                                <td>
                                                    <div class="btn-group btn-group-sm status-group">
                                                        <button type="button"
                                                            class="btn {{ $lsStatus == 1 ? 'btn-success active' : 'btn-outline-success' }}"
                                                            data-value="1">Masuk</button>
                                                        <button type="button"
                                                            class="btn {{ $lsStatus == 2 ? 'btn-warning active' : 'btn-outline-warning' }}"
                                                            data-value="2">Izin</button>
                                                        <button type="button"
                                                            class="btn {{ $lsStatus == 3 ? 'btn-danger active' : 'btn-outline-danger' }}"
                                                            data-value="3">Alpha</button>
                                                    </div>

                                                    <input type="hidden"
                                                        name="data[{{ $pegawai->uuid }}_long][is_lembur]" value="1">
                                                    <input type="hidden" name="data[{{ $pegawai->uuid }}_long][shift]"
                                                        value="{{ $lsShift }}">
                                                    <input type="hidden" class="status-input"
                                                        name="data[{{ $pegawai->uuid }}_long][status]"
                                                        value="{{ $lsStatus }}">
                                                </td>
                                                <td>
                                                    <input type="number"
                                                        name="data[{{ $pegawai->uuid }}_long][pencapaian]"
                                                        class="form-control" placeholder="Pencapaian"
                                                        value="{{ $lsPencapaian }}">
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger remove-longshift-btn">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </td>
                                            </tr>

                                            <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                // Sembunyikan tombol longshift pada baris asli
                                                const originalRow = document.querySelector(
                                                    '[data-pegawai="{{ $pegawai->uuid }}"]').closest('tr');
                                                const originalLongshiftBtn = originalRow.querySelector(
                                                    '.longshift-btn');
                                                if (originalLongshiftBtn) {
                                                    originalLongshiftBtn.style.display = 'none';
                                                }
                                            });
                                            </script>
                                            @endif

                                            @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No Data
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer clearfix">

                                    <div class="float-end">
                                        {{ $pegawais->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>
                                        Simpan</button>
                                    <a href="{{ route('absensiUpdate.index') }}" class="btn btn-md btn-danger">
                                        <i class="fa fa-times"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <!--end::Header-->
                <!--begin::Form-->
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
function changeDate(days) {

    let input = document.getElementById('tanggal_absen');
    let date = new Date(input.value);
    date.setDate(date.getDate() + days);
    let newDate = date.toISOString().split('T')[0];
    input.value = newDate;
    input.form.submit();
}

// Fungsi untuk menambahkan baris longshift
function addLongShiftRow(pegawaiData, originalRow) {
    // Cek apakah sudah ada longshift untuk pegawai ini
    const existingLongShift = document.querySelector(`tr.longshift-row[data-pegawai-uuid="${pegawaiData.uuid}"]`);
    if (existingLongShift) {
        alert('Long Shift untuk pegawai ini sudah ada!');
        return;
    }

    const tbody = document.querySelector('tbody');
    const newRow = document.createElement('tr');
    newRow.className = 'align-middle longshift-row';
    newRow.setAttribute('data-pegawai-uuid', pegawaiData.uuid);

    // Tentukan shift baru (kebalikan dari shift saat ini)
    const currentShift = pegawaiData.shift;
    // const newShift = currentShift === 'Pagi' ? 'Malam' : 'Pagi';
    const newShift = currentShift == 1 ? 2 : 1;
    const shiftLabel = newShift == 1 ? 'Pagi' : 'Malam';

    newRow.innerHTML = `
        <td>#</td>
        <td class="text-truncate">
            <span class="badge bg-success ms-1">Long Shift</span>
        </td>
        <td></td>
        <td></td>
         <td>${shiftLabel}</td>

        <td>
            <div class="btn-group btn-group-sm status-group">
                <button type="button" class="btn btn-outline-success active" data-value="1">Masuk</button>
                <button type="button" class="btn btn-outline-warning" data-value="2">Izin</button>
                <button type="button" class="btn btn-outline-danger" data-value="3">Alpha</button>
                </div>
                
                <!-- 🔥 FLAG LEMBUR -->
                <input type="hidden"
                name="data[${pegawaiData.uuid}_long][is_lembur]"
                value="1">

                <!-- 🔥 SHIFT -->
                <input type="hidden"
                name="data[${pegawaiData.uuid}_long][shift]"
                value="${newShift}">
                    
                <!-- 🔥 FIX -->
                <input type="hidden"
                class="status-input"
                name="data[${pegawaiData.uuid}_long][status]"
                value="1">
        </td>

        <td>
            <input type="number"
                name="data[${pegawaiData.uuid}_long][pencapaian]"
                class="form-control"
                placeholder="Pencapaian">
        </td>

        <td>
            <button type="button" class="btn btn-sm btn-danger remove-longshift-btn">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;

    // Tambahkan baris baru setelah baris asli
    tbody.insertBefore(newRow, originalRow.nextSibling);

    // Update nomor urut
    updateRowNumbers();

    // Tambahkan event listener untuk tombol hapus
    newRow.querySelector('.remove-longshift-btn').addEventListener('click', function() {
        newRow.remove();
        updateRowNumbers();
        // Tampilkan kembali tombol longshift pada baris asli
        const originalLongshiftBtn = originalRow.querySelector('.longshift-btn');
        if (originalLongshiftBtn) {
            originalLongshiftBtn.style.display = 'inline-block';
        }
    });

    // Tambahkan event listener untuk status group
    setupStatusGroup(newRow.querySelector('.status-group'));

    // Sembunyikan tombol longshift pada baris asli
    const originalLongshiftBtn = originalRow.querySelector('.longshift-btn');
    if (originalLongshiftBtn) {
        originalLongshiftBtn.style.display = 'none';
    }

    // console.log('Baris longshift berhasil ditambahkan untuk:', pegawaiData.nama);
}

// Fungsi untuk mengatur event listener status group
function setupStatusGroup(statusGroup) {
    statusGroup.querySelectorAll("button").forEach(button => {
        button.addEventListener("click", function() {
            let group = this.closest(".status-group");

            group.querySelectorAll("button").forEach(btn => {
                btn.classList.remove("active");

                if (btn.classList.contains("btn-success")) {
                    btn.classList.remove("btn-success");
                    btn.classList.add("btn-outline-success");
                }

                if (btn.classList.contains("btn-warning")) {
                    btn.classList.remove("btn-warning");
                    btn.classList.add("btn-outline-warning");
                }

                if (btn.classList.contains("btn-danger")) {
                    btn.classList.remove("btn-danger");
                    btn.classList.add("btn-outline-danger");
                }
            });

            this.classList.add("active");

            if (this.classList.contains("btn-outline-success")) {
                this.classList.remove("btn-outline-success");
                this.classList.add("btn-success");
            }

            if (this.classList.contains("btn-outline-warning")) {
                this.classList.remove("btn-outline-warning");
                this.classList.add("btn-warning");
            }

            if (this.classList.contains("btn-outline-danger")) {
                this.classList.remove("btn-outline-danger");
                this.classList.add("btn-danger");
            }

            let hiddenInput = group.parentElement.querySelector(".status-input");
            hiddenInput.value = this.dataset.value;
        });
    });
}

// Fungsi untuk update nomor urut
function updateRowNumbers() {
    const rows = document.querySelectorAll('tbody tr:not(.longshift-row)');
    rows.forEach((row, index) => {
        const firstCell = row.querySelector('td:first-child');
        if (firstCell) {
            firstCell.textContent = index + 1;
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {

    // Setup status group untuk baris-baris awal
    document.querySelectorAll(".status-group").forEach(statusGroup => {
        setupStatusGroup(statusGroup);
    });

    // Setup longshift button
    document.querySelectorAll(".longshift-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const pegawaiData = {
                uuid: this.dataset.pegawai,
                nama: this.dataset.nama,
                shift: this.dataset.shift,
            };

            const originalRow = this.closest('tr');
            addLongShiftRow(pegawaiData, originalRow);
        });
    });

    // 🔥 SETUP EVENT LISTENER UNTUK TOMBOL REMOVE LONGSHIFT YANG SUDAH ADA DARI SERVER
    document.querySelectorAll(".remove-longshift-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            const longshiftRow = this.closest('tr.longshift-row');
            const pegawaiUuid = longshiftRow.dataset.pegawaiUuid;

            // Hapus baris longshift
            longshiftRow.remove();

            // Tampilkan kembali tombol longshift pada baris asli
            const originalLongshiftBtn = document.querySelector(
                `.longshift-btn[data-pegawai="${pegawaiUuid}"]`);
            if (originalLongshiftBtn) {
                originalLongshiftBtn.style.display = 'inline-block';
            }

            updateRowNumbers();
        });
    });

});

document.querySelectorAll('.status-group button').forEach(btn => {
    btn.addEventListener('click', () => {

        const group = btn.closest('.status-group');
        const container = btn.closest('td');
        const input = container.querySelector('.status-input');

        const buttons = group.querySelectorAll('button');

        // reset hanya dalam group ini
        buttons.forEach(b => b.classList.remove('active'));

        // set active
        btn.classList.add('active');

        // update value
        if (input) {
            input.value = btn.dataset.value;
        }

        // console.log('SET:', btn.dataset.value);
    });
});
</script>
@endpush