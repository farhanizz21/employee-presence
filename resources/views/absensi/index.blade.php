@extends('layouts.app')

@section('content')

<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0">Absensi</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Absensi
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
                                    <h5 class="card-title mb-0">Data Absensi Pegawai</h5>
                                    <div class="col-auto text-end">
                                        <a href="{{ route('absensi.rekap')}}" class="btn btn-primary shadow-sm">
                                            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Body Card --}}
                        <div class="card-body">
                            {{-- Filter --}}
                            <form method="GET" action="{{ route('absensi.index') }}"
                                class="mb-3 d-flex flex-wrap align-items-center gap-2">
                                <div class="me-2">
                                    <label for="periode_uuid" class="form-label fw-semibold mb-1">Pilih
                                        Periode:</label>
                                    <select name="periode_uuid" id="periode_uuid" onchange="this.form.submit()"
                                        class="form-select" style="min-width: 250px;">
                                        @foreach($periodes as $p)
                                        <option value="{{ $p->uuid }}" {{ $periodeUuid == $p->uuid ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}
                                            - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>

                            {{-- 🔹 Filter Pencarian & Tambahan --}}
                            <form method="GET" action="{{ route('absensi.index') }}"
                                class="row g-2 align-items-center mb-4">

                                <div class="col-md-3">
                                    <input type="text" name="search" class="form-control"
                                        placeholder="Cari nama pegawai..." value="{{ request('search') }}">
                                </div>

                                <div class="col-md-3">
                                    <select name="grup_uuid" class="form-select">
                                        <option value="">Semua Grup</option>
                                        @foreach($grups as $grup)
                                        <option value="{{ $grup->uuid }}"
                                            {{ request('grup_uuid') == $grup->uuid ? 'selected' : '' }}>
                                            {{ $grup->nama }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <select name="shift" class="form-select">
                                        <option value="">Semua Shift</option>
                                        <option value="Pagi" {{ request('shift') == 'Pagi' ? 'selected' : '' }}>
                                            Pagi
                                        </option>
                                        <option value="Malam" {{ request('shift') == 'Malam' ? 'selected' : '' }}>
                                            Malam
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1">
                                        <i class="fas fa-search"></i> Filter
                                    </button>
                                    <a href="{{ route('absensi.index') }}" class="btn btn-warning flex-grow-1">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>

                            {{-- Table --}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm table-hover">
                                    <thead class="table-dark text-center align-middle">
                                        <tr>
                                            <th rowspan="2" class="align-middle">No.</th>
                                            <th rowspan="2" class="align-middle">Nama Karyawan</th>
                                            <th rowspan="2" class="align-middle">Ket.</th>
                                            @foreach ($dates as $tgl)
                                            <th class="align-middle">
                                                {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
                                            </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pegawais as $i => $pegawai)
                                        {{-- Baris Status --}}
                                        <tr>
                                            <td rowspan="6" class="text-center align-middle fw-semibold">
                                                {{ $i + 1 }}
                                            </td>
                                            <td rowspan="6" class="align-middle fw-semibold">
                                                {{ $pegawai->nama }}</td>
                                            <td class="align-middle fw-semibold">Status</td>
                                            @foreach ($dates as $tgl)
                                            @php
                                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                            @endphp
                                            <td class="text-center align-middle">
    <span class="cell-status" data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">{{ $absensi->status ?? '-' }}</span>
</td>

                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="align-middle fw-semibold">Grup</td>
                                            @foreach ($dates as $tgl)
                                            @php
                                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                            @endphp
                                            <td class="text-center align-middle">
                                                <span class="cell-grup_sb" data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">
                                                {{ $absensi->grup->nama ?? '-' }}</span></td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="align-middle fw-semibold">Shift</td>
                                            @foreach ($dates as $tgl)
                                            @php
                                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                            @endphp
                                            <td class="text-center align-middle">
    <span class="cell-grup" data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">{{ $absensi->grup_uuid ?? '-' }}</span>
</td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="align-middle fw-semibold">Jobdesk</td>
                                            @foreach ($dates as $tgl)
                                            @php
                                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                            @endphp
                                            <td class="text-center align-middle">
    <span class="cell-jabatan" data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">{{ $absensi->jabatan->jabatan ?? '-' }}</span>
</td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="align-middle fw-semibold">Hasil Produksi</td>
                                            @foreach ($dates as $tgl)
                                            @php
                                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                            @endphp
                                            <td class="text-center align-middle">
    <span class="cell-pencapaian" data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">{{ $absensi->pencapaian ?? '-' }}</span>
</td>
                                            @endforeach
                                        </tr>

                                        <tr>
                                            <td class="align-middle fw-semibold">Aksi</td>
                                            @foreach ($dates as $tgl)
                                            @php
                                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                            @endphp
                                            <td class="text-center align-middle">
                                                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#ubahModal" data-pegawai="{{ $pegawai->uuid }}"
                                                    data-tanggal="{{ $tgl }}" data-nama="{{ $pegawai->nama }}"
                                                    data-status="{{ $absensi->status ?? 'Masuk' }}"
                                                    data-shift="{{ $absensi->grup_uuid ?? 'Pagi' }}"
                                                    data-jabatan="{{ $absensi->jabatan->uuid ?? '' }}"
                                                    data-grup="{{ $absensi->grup_uuid ?? '' }}"
                                                    data-grupsb="{{ $absensi->grup_sb ?? '' }}"
                                                    data-pencapaian="{{ $absensi->pencapaian ?? '' }}">
                                                    <i class="fas fa-edit"></i>
                                                    Edit
                                                </button>
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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


    {{-- Modal Ubah --}}
    <div class="modal fade" id="ubahModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="modalForm" onsubmit="return false;">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Absensi</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="modalPegawai">
                        <input type="hidden" id="modalTanggal">

                        <div class="form-group">
                            <label>Nama Pegawai</label>
                            <input type="text" id="modalNama" class="form-control" readonly>
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
                            <label>Grup</label>
                            <select id="modalGrupsb" class="form-control">
                                <option value="">-- Pilih Grup --</option>
                                @foreach($grups as $grsb)
                                <option value="{{ $grsb->uuid }}" data-grupsb="{{ $grsb->grup_sb }}">
                                    {{ $grsb->nama }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Shift</label>
                            <select id="modalShift" class="form-control">
                                <option value="Pagi">Pagi</option>
                                <option value="Malam">Malam</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Jobdesk</label>
                            <select id="modalJabatan" class="form-control">
                                <option value="">-- Pilih Jabatan --</option>
                                @foreach($jabatans as $jabatan)
                                <option value="{{ $jabatan->uuid }}" data-harian="{{ $jabatan->harian }}">
                                    {{ $jabatan->jabatan }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" id="pencapaianGroup" style="display:none;">
                            <label>Pencapaian</label>
                            <input type="text" id="modalPencapaian" class="form-control">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" id="modalSaveBtn" class="btn btn-primary">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
let currentCell = null;

// Event saat modal akan tampil
$('#ubahModal').on('show.bs.modal', function(event) {
    var button = event.relatedTarget; // DOM element
    var modal = $(this); // jQuery object

    currentCell = button.closest('td');

    // Set nilai awal modal
    modal.find('#modalPegawai').val(button.dataset.pegawai);
    modal.find('#modalTanggal').val(button.dataset.tanggal);
    modal.find('#modalNama').val(button.dataset.nama);
    modal.find('#modalStatus').val(button.dataset.status || 'Masuk');
    modal.find('#modalShift').val(button.dataset.shift || 'Pagi');
    modal.find('#modalJabatan').val(button.dataset.jabatan || '');
    modal.find('#modalGrupsb').val(button.dataset.grupsb || '');
    modal.find('#modalPencapaian').val(button.dataset.pencapaian || '');

    // ✅ Cek apakah jabatan harian == 2
    let selectedOption = modal.find('#modalJabatan')[0].options[
        modal.find('#modalJabatan')[0].selectedIndex
    ];
    let harian = selectedOption ? selectedOption.dataset.harian : null;

    if (harian == '2') {
        modal.find('#pencapaianGroup').show();
    } else {
        modal.find('#pencapaianGroup').hide();
        modal.find('#modalPencapaian').val('');
    }
});


// Event listener Jobdesk di modal
document.getElementById("modalJabatan").addEventListener("change", function() {
    let selectedOption = this.options[this.selectedIndex];
    let harian = selectedOption.dataset.harian;
    if (harian == '2') {
        document.getElementById("pencapaianGroup").style.display = 'block';
    } else {
        document.getElementById("pencapaianGroup").style.display = 'none';
        document.getElementById("modalPencapaian").value = '';
    }
});

// Simpan perubahan
document.getElementById("modalSaveBtn").addEventListener("click", function() {
    if (!currentCell) return;

    let pegawai_uuid = document.getElementById("modalPegawai").value;
    let tanggal = document.getElementById("modalTanggal").value;
    let status = document.getElementById("modalStatus").value;
    let shift = document.getElementById("modalShift").value;
    let grup_uuid = shift;
    let jabatan_uuid = document.getElementById("modalJabatan").value;
    let grup_sb = document.getElementById("modalGrupsb").value;
    let pencapaian = document.getElementById("modalPencapaian").value;

    fetch("{{ route('absensi.updateCell') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                pegawai_uuid: pegawai_uuid,
                tanggal: tanggal,
                status: status,
                shift: shift,
                grup_sb: grup_sb,
                jabatan_uuid: jabatan_uuid,
                grup_uuid: grup_uuid,
                pencapaian: pencapaian
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
    console.log("Data berhasil disimpan:", data);

    // Ambil data pegawai & tanggal
    const pegawai_uuid = document.getElementById("modalPegawai").value;
    const tanggal = document.getElementById("modalTanggal").value;

    // Update cell status
    const cellStatus = document.querySelector(`.cell-status[data-pegawai='${pegawai_uuid}'][data-tanggal='${tanggal}']`);
    if (cellStatus) cellStatus.textContent = status;

    // Update cell shift
    const cellGrup = document.querySelector(`.cell-grup[data-pegawai='${pegawai_uuid}'][data-tanggal='${tanggal}']`);
    if (cellGrup) cellGrup.textContent = shift;

    // Update cell jabatan
    const cellJabatan = document.querySelector(`.cell-jabatan[data-pegawai='${pegawai_uuid}'][data-tanggal='${tanggal}']`);
    if (cellJabatan) {
        const jabatanText = document.querySelector(`#modalJabatan option[value='${jabatan_uuid}']`)?.textContent || '-';
        cellJabatan.textContent = jabatanText;
    }

    // Update cell pencapaian
    const cellPencapaian = document.querySelector(`.cell-pencapaian[data-pegawai='${pegawai_uuid}'][data-tanggal='${tanggal}']`);
    if (cellPencapaian) cellPencapaian.textContent = pencapaian || '-';

    // Tutup modal
    $('#ubahModal').modal('hide');
}

        })
        .catch(err => console.error("Error:", err));

});
</script>

@endsection