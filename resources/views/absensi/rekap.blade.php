@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Tambah Data Absensi</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Tambah Data Absensi
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
                                    <h5 class="card-title mb-0">Tambah Data Absensi</h5>
                                </div>
                            </div>
                        </div>
                        {{-- Body Card --}}
                        <div class="card-body">

                            {{-- Filter Tanggal --}}
                            <form method="GET" action="{{ route('absensi.rekap') }}" class="mb-4">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <label for="tanggal_range" class="col-form-label fw-bold">
                                            <i class="fas fa-calendar-alt me-1"></i> Periode:
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <div class="input-group">
                                            <span class="input-group-text bg-primary text-white">
                                                <i class="fas fa-calendar-day"></i>
                                            </span>
                                            <input type="text" name="tanggal_range" id="tanggal_range"
                                                class="form-control" value="{{ request('tanggal_range') }}" readonly
                                                style="cursor: pointer; background-color: #fff;">
                                        </div>
                                        <input type="hidden" name="tanggal_mulai" id="tanggal_mulai">
                                        <input type="hidden" name="tanggal_selesai" id="tanggal_selesai">
                                    </div>
                                </div>
                            </form>

                            {{-- 🔹 Filter Pencarian & Tambahan --}}
                            <form method="GET" action="{{ route('absensi.rekap') }}"
                                class="row g-2 align-items-center mb-4">

                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                                </div>

                                <div class="col-md-2">
                                    <select name="jabatan_uuid" class="form-select">
                                        <option value="">Semua Jabatan</option>
                                        @foreach($jabatans as $jabatan)
                                        <option value="{{ $jabatan->uuid }}"
                                            {{ request('jabatan_uuid') == $jabatan->uuid ? 'selected' : '' }}>
                                            {{ $jabatan->jabatan }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <select name="grup_sb" class="form-select">
                                        <option value="">Semua Grup</option>
                                        @foreach($grupSbs as $grupSb)
                                        <option value="{{ $grupSb->uuid }}"
                                            {{ request('grup_sb') == $grupSb->uuid ? 'selected' : '' }}>
                                            {{ $grupSb->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- 🔹 Filter Shift --}}
                                <div class="col-md-2">
                                    <select name="shift" class="form-select">
                                        <option value="">Semua Shift</option>
                                        <option value="pagi" {{ request('shift') == 'pagi' ? 'selected' : '' }}>Pagi
                                        </option>
                                        <option value="malam" {{ request('shift') == 'malam' ? 'selected' : '' }}>Malam
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('absensi.rekap') }}" class="btn btn-warning flex-grow-1">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>


                            {{-- Form Input Absensi --}}
                            <form action="{{ route('absensi.simpanRekap') }}" method="POST">
                                @csrf
                                <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                                <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">

                                <div class="table-responsive"
                                    style="max-height: 75vh; overflow: auto; border-radius: 8px; border: 1px solid #dee2e6;">
                                    <table
                                        class="table table-bordered table-striped table-sm table-hover align-middle mb-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th class="sticky-col first-col text-center align-middle">Nama Pegawai
                                                </th>
                                                <th class="sticky-col second-col text-center align-middle">Default Grup
                                                </th>
                                                <th class="sticky-col third-col text-center align-middle">Default Shift
                                                </th>
                                                <th
                                                    class="sticky-col fourth-col text-center align-middle border-separator">
                                                    Default
                                                    Jobdesk</th>

                                                @foreach($dates as $tgl)
                                                <th class="text-center align-middle" style="min-width:140px;">
                                                    {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                                                </th>
                                                @endforeach
                                            </tr>
                                        </thead>


                                        <tbody>
                                            @foreach($pegawais as $pegawai)
                                            <tr>
                                                <td
                                                    class="sticky-col first-col text-center align-middle fw-semibold bg-white">
                                                    {{ $pegawai->nama }}
                                                </td>
                                                <td class="sticky-col second-col text-center align-middle bg-white">
                                                    {{ $pegawai->grupSb->nama ?? '-' }}
                                                </td>
                                                <td class="sticky-col third-col text-center align-middle bg-white">
                                                    {{ $pegawai->grup_uuid ?? '-' }}
                                                </td>
                                                <td
                                                    class="sticky-col fourth-col text-center align-middle bg-white border-separator">
                                                    {{ $pegawai->jabatan->jabatan ?? '-' }}
                                                </td>
                                                @foreach($dates as $tgl)
                                                <td class="text-center align-middle">
                                                    {{-- Select Status --}}
                                                    <select name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][status]"
                                                        class="form-select form-select-sm status-select"
                                                        style="font-size:0.75rem; padding:2px 4px;">
                                                        <option value="Masuk">Masuk</option>
                                                        <option value="Alpha">Alpha</option>
                                                        <option value="Izin">Izin</option>
                                                        <option value="Telat">Telat</option>
                                                        <option value="Lembur">Lembur</option>
                                                    </select>

                                                    {{-- Hidden shift & grup (default) --}}
                                                    <input type="hidden"
                                                        name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][shift]"
                                                        class="shift-input" value="{{ $pegawai->grup_uuid }}">
                                                    <input type="hidden"
                                                        name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][jabatan_uuid]"
                                                        class="jabatan-input" value="{{ $pegawai->jabatan->uuid }}">
                                                    <input type="hidden"
                                                        name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][grup_sb]"
                                                        class="grupsb-input" value="{{ $pegawai->grup_sb ?? '-' }}">

                                                    {{-- Placeholder untuk info perubahan (kosong dulu, diisi via JS jika berubah) --}}
                                                    <div class="change-info mt-1 small text-muted"></div>

                                                    {{-- Input Pencapaian (default show jika jabatan->harian == 2) --}}
                                                    <input type="number"
                                                        name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][pencapaian]"
                                                        class="form-control form-control-sm pencapaian-input"
                                                        style="margin-top:4px; font-size:0.75rem; padding:2px 4px; {{ $pegawai->jabatan->harian == 2 ? '' : 'display:none;' }}"
                                                        placeholder="Pencapaian">

                                                    {{-- Tombol modal --}}
                                                    <button type="button"
                                                        class="btn btn-light btn-sm p-1  border-0 shadow-none edit-btn"
                                                        data-toggle="modal" data-target="#ubahModal"
                                                        data-pegawai="{{ $pegawai->uuid }}"
                                                        data-nama="{{ $pegawai->nama }}" data-tanggal="{{ $tgl }}"
                                                        data-shift="{{ $pegawai->grup_uuid ?? '' }}"
                                                        data-grup="{{ $pegawai->grup_sb ?? '' }}"
                                                        data-jabatan="{{ $pegawai->jabatan_uuid ?? '' }}"
                                                        data-status="{{ $absensi[$pegawai->uuid][$tgl]['status'] ?? '' }}"
                                                        title="Ubah Data">
                                                        <i class="fas fa-edit text-warning"></i>
                                                        <span class="text-secondary small">Edit</span>
                                                    </button>

                                                </td>
                                                @endforeach
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @if($pegawais->count())
                                    <div class="mt-3 d-flex justify-content-center">
                                        {{ $pegawais->withQueryString()->links('pagination::bootstrap-4') }}
                                    </div>
                                    @else
                                    <div class="alert alert-warning text-center mt-3">
                                        <i class="fas fa-exclamation-circle"></i> Tidak ada data pegawai ditemukan.
                                    </div>
                                    @endif
                                </div>

                                <button type="submit" class="btn btn-success mt-3">
                                    <i class="fas fa-save fa-sm text-white-50"></i> Simpan Rekap
                                </button>

                                <a href="{{ route('absensi.index')}}" class="btn btn-danger shadow-sm mt-3 ms-3">
                                    <i class="fas fa-times fa-sm text-white-50"></i> Batal
                                </a>

                            </form>
                        </div>

                        {{-- Modal --}}
                        <div class="modal fade" id="ubahModal" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Ubah Shift & Jobdesk</h5>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" id="modalPegawai">
                                        <input type="hidden" id="modalTanggal">

                                        <div class="form-group">
                                            <label>Nama Pegawai</label>
                                            <input type="text" id="modalNama" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label>Grup</label>
                                            <select id="modalGrupSb" class="form-control">
                                                @foreach($grupSbs as $grup)
                                                <option value="{{ $grup->uuid }}">{{ $grup->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select id="modalStatus" class="form-control">
                                                <option value="Alpha">Alpha</option>
                                                <option value="Masuk">Masuk</option>
                                                <option value="Izin">Izin</option>
                                                <option value="Telat">Telat</option>
                                                <option value="Lembur">Lembur</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Shift</label>
                                            <select id="modalGrup" class="form-control">
                                                @foreach(['Pagi', 'Malam'] as $shift)
                                                <option value="{{ $shift }}"
                                                    {{ $shift == ($pegawai->grup_uuid ?? '') ? 'selected' : '' }}>
                                                    {{ $shift }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Jobdesk</label> <!-- jobdesk = jabatan -->
                                            <select id="modalJabatan" class="form-control">
                                                @foreach($jabatans as $jabatan)
                                                <option value="{{ $jabatan->uuid }}"
                                                    data-harian="{{ $jabatan->harian }}">
                                                    {{ $jabatan->jabatan }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" id="modalSaveBtn" class="btn btn-primary">Simpan
                                            Perubahan</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer clearfix">
                            <!-- pagination -->
                            <div class="float-end">
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
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const modalPegawai = document.getElementById("modalPegawai");
        const modalTanggal = document.getElementById("modalTanggal");
        const modalNama = document.getElementById("modalNama");
        const modalGrup = document.getElementById("modalGrup");
        const modalGrupSb = document.getElementById("modalGrupSb");
        const modalJabatan = document.getElementById("modalJabatan");
        const modalStatus = document.getElementById("modalStatus");
        const modalSaveBtn = document.getElementById("modalSaveBtn");

        // Buat elemen pencapaian di modal
        const modalPencapaianWrapper = document.createElement("div");
        const modalPencapaian = document.createElement("input");
        modalPencapaianWrapper.classList.add("form-group", "mt-2");
        modalPencapaian.type = "number";
        modalPencapaian.classList.add("form-control");
        modalPencapaian.placeholder = "Pencapaian";
        modalPencapaianWrapper.appendChild(modalPencapaian);
        document.querySelector("#ubahModal .modal-body").appendChild(modalPencapaianWrapper);
        modalPencapaianWrapper.style.display = "none";

        let targetCell = null;
        const qs = (el, sel) => el.querySelector(sel);

        // --- Fungsi: tampil/sembunyikan input pencapaian di modal ---
        function handleJabatanHarian() {
            const opt = modalJabatan.options[modalJabatan.selectedIndex];
            const harian = opt?.getAttribute("data-harian");
            const status = modalStatus.value; // ambil status dari modal

            // Jika status Izin atau Alpha → sembunyikan pencapaian apapun jabatan-nya
            if (status === "Izin" || status === "Alpha") {
                modalPencapaianWrapper.style.display = "none";
                modalPencapaian.value = '';
                return; // keluar dari fungsi
            }

            // Kalau bukan izin/alpha → cek apakah jabatan harian
            if (harian === "2") {
                modalPencapaianWrapper.style.display = "block";
                const pencapaianInput = qs(targetCell, ".pencapaian-input");
                modalPencapaian.value = pencapaianInput ? pencapaianInput.value : '';
            } else {
                modalPencapaianWrapper.style.display = "none";
                modalPencapaian.value = '';
            }
        }


        // --- Fungsi: tampil/sembunyikan input pencapaian di tabel (berdasarkan status) ---
        function handleStatusChange(select) {
            const cell = select.closest("td");
            const pencapaianInput = cell.querySelector(".pencapaian-input");
            if (!pencapaianInput) return;

            const status = select.value;
            if (status === "Izin" || status === "Alpha") {
                pencapaianInput.style.display = "none";
                pencapaianInput.value = "";
            } else {
                const harian = cell.querySelector(".jabatan-input")?.dataset.harian;
                if (harian == "2") pencapaianInput.style.display = "block";
            }
        }

        // Listener untuk setiap dropdown status di tabel
        document.querySelectorAll(".status-select").forEach(select => {
            handleStatusChange(select); // jalankan saat load pertama
            select.addEventListener("change", () => handleStatusChange(select));
        });

        // --- Klik tombol edit ---
        document.querySelectorAll(".edit-btn").forEach(btn => {
            btn.addEventListener("click", () => {
                targetCell = btn.closest("td");

                modalPegawai.value = btn.dataset.pegawai;
                modalTanggal.value = btn.dataset.tanggal;
                modalNama.value = btn.dataset.nama;
                modalGrup.value = btn.dataset.shift;
                modalGrupSb.value = btn.dataset.grup;
                modalStatus.value = qs(targetCell, ".status-select").value;

                // ✅ ambil jabatan langsung dari dataset (karena kolom pegawai.jabatan_uuid)
                modalJabatan.value = btn.dataset.jabatan;

                // pastikan event change dijalankan agar tampil pencapaian jika harian == 2
                modalJabatan.dispatchEvent(new Event("change"));
                handleJabatanHarian();
            });
        });

        // ubah input pencapaian modal saat ganti jabatan
        modalJabatan.addEventListener("change", handleJabatanHarian);
        modalStatus.addEventListener("change", handleJabatanHarian);

        // --- Klik tombol simpan di modal ---
        modalSaveBtn.addEventListener("click", () => {
            const hiddenShift = qs(targetCell, ".shift-input");
            const hiddenGrupSb = qs(targetCell, ".grupsb-input");
            const hiddenJabatan = qs(targetCell, ".jabatan-input");
            const statusSelect = qs(targetCell, ".status-select");
            const pencapaianInput = qs(targetCell, ".pencapaian-input");
            const infoBox = qs(targetCell, ".change-info");

            const grupText = modalGrup.options[modalGrup.selectedIndex]?.text || '';
            const jabatanText = modalJabatan.options[modalJabatan.selectedIndex]?.text || '';
            const grupSbText = modalGrupSb.options[modalGrupSb.selectedIndex]?.text || '';
            const pencapaianVal = modalPencapaianWrapper.style.display === "block" ? modalPencapaian
                .value : '';
            const changes = [];

            // update hidden shift & jabatan
            if (hiddenShift && hiddenShift.value !== modalGrup.value) {
                hiddenShift.value = modalGrup.value;
                changes.push(`Shift: <span class="text-primary">${grupText}</span>`);
            }
            if (hiddenJabatan && hiddenJabatan.value !== modalJabatan.value) {
                hiddenJabatan.value = modalJabatan.value;
                changes.push(`Jobdesk: <span class="text-primary">${jabatanText}</span>`);
            }
            // update grup_sb
            if (hiddenGrupSb && hiddenGrupSb.value !== modalGrupSb.value) {
                hiddenGrupSb.value = modalGrupSb.value;
                changes.push(`Grup SB: <span class="text-primary">${grupSbText}</span>`);
            }


            // update status select di cell
            if (statusSelect) {
                statusSelect.value = modalStatus.value;
                handleStatusChange(statusSelect); // sembunyikan pencapaian jika izin/alpha
            }

            // update input pencapaian di cell
            if (pencapaianInput) {
                if (modalPencapaianWrapper.style.display === "block") {
                    pencapaianInput.style.display = "block";
                    pencapaianInput.value = pencapaianVal;
                } else {
                    pencapaianInput.style.display = "none";
                    pencapaianInput.value = '';
                }
            }

            // tampilkan badge perubahan
            if (infoBox) {
                infoBox.innerHTML = changes.length ?
                    `<div class="badge bg-warning text-dark">${changes.join(' | ')}</div>` : '';
            }

            $("#ubahModal").modal("hide");
        });
    });
    </script>


    <script>
    $(function() {
        $('#tanggal_range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoUpdateInput: false
        });

        $('#tanggal_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format(
                'YYYY-MM-DD'));
            $('#tanggal_mulai').val(picker.startDate.format('YYYY-MM-DD'));
            $('#tanggal_selesai').val(picker.endDate.format('YYYY-MM-DD'));

            // langsung submit form filter
            $(this).closest("form").submit();
        });
    });
    </script>

    <style>
    /* Garis pemisah tebal antara kolom data pegawai dan tanggal */
    .border-separator {
        border-right: 3px solid #6c757d !important;
        /* abu-abu tegas */
        box-shadow: 2px 0 4px rgba(0, 0, 0, 0.08);
        /* sedikit bayangan agar tampak naik */
        z-index: 15 !important;
    }

    /* Pastikan header pemisah terlihat di atas semua */
    .table thead .border-separator {
        background-color: #212529 !important;
        color: #fff;
        z-index: 20 !important;
    }

    /* --- Table layout --- */
    .table {
        border-collapse: separate !important;
        border-spacing: 0;
    }

    /* --- Freeze header --- */
    .table thead th {
        position: sticky;
        top: 0;
        z-index: 12;
        background-color: #212529 !important;
        color: #fff;
        text-align: center;
        vertical-align: middle;
    }

    /* --- Freeze 4 kolom kiri --- */
    .sticky-col {
        position: sticky;
        background-color: #fff !important;
        z-index: 11;
    }

    .table thead .sticky-col {
        z-index: 13 !important;
        background-color: #343a40 !important;
        box-shadow: inset 1px 0 0 #495057;
    }

    .first-col {
        left: 0;
        min-width: 100px;
    }

    .second-col {
        left: 100px;
        min-width: 80px;
    }

    .third-col {
        left: 180px;
        min-width: 80px;
    }

    .fourth-col {
        left: 260px;
        min-width: 80px;
    }

    /* --- Biar header & kolom kiri sejajar --- */
    .table-responsive {
        overflow: auto;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }

    /* --- Scrollbar style --- */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }

    .table-responsive::-webkit-scrollbar-thumb {
        background: #adb5bd;
        border-radius: 4px;
    }

    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #6c757d;
    }

    /* --- Border lembut --- */
    .table td,
    .table th {
        border: 1px solid #dee2e6;
    }

    .edit-btn {
        background-color: transparent !important;
        transition: all 0.2s ease;
        font-weight: 500;
    }

    .edit-btn:hover {
        background-color: #fff8e1 !important;
        /* kuning lembut saat hover */
        transform: scale(1.03);
        border-radius: 6px;
    }

    .edit-btn i {
        font-size: 0.85rem;
        opacity: 0.85;
    }

    .edit-btn span {
        font-size: 0.75rem;
    }

    .edit-btn:hover i,
    .edit-btn:hover span {
        opacity: 1;
        color: #f39c12 !important;
    }
    </style>
    @endsection