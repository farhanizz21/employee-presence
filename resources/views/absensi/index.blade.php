@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow mb-4">
        {{-- Header Card --}}
        <div class="card-header">
            <h5 class="card-title mb-0">Absensi Pegawai</h5>
            <div class="card-tools ml-auto">
                <a href="{{ route('absensi.rekap')}}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Data
                </a>
            </div>
        </div>

        {{-- Body Card --}}
        <div class="card-body">
            {{-- Filter --}}
            <form method="GET" action="{{ route('absensi.index') }}" class="mb-3 d-flex align-items-center">
                <label class="me-2 mb-0">Pilih Periode:</label>
                <select name="periode_uuid" onchange="this.form.submit()" class="form-control"
                    style="max-width: 250px;">
                    @foreach($periodes as $p)
                    <option value="{{ $p->uuid }}" {{ $periodeUuid == $p->uuid ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }}
                        - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
                    </option>
                    @endforeach
                </select>
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
                            <th class="align-middle">{{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pegawais as $i => $pegawai)
                        {{-- Baris Status --}}
                        <tr>
                            <td rowspan="5" class="text-center align-middle fw-semibold">{{ $i + 1 }}</td>
                            <td rowspan="5" class="align-middle fw-semibold">{{ $pegawai->nama }}</td>
                            <td class="align-middle fw-semibold">Status</td>
                            @foreach ($dates as $tgl)
                            @php
                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                            @endphp
                            <td class="text-center align-middle">{{ $absensi->status ?? '-' }}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="align-middle fw-semibold">Shift</td>
                            @foreach ($dates as $tgl)
                            @php
                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                            @endphp
                            <td class="text-center align-middle">{{ $absensi->shift ?? '-' }}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="align-middle fw-semibold">Jobdesk</td>
                            @foreach ($dates as $tgl)
                            @php
                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                            @endphp
                            <td class="text-center align-middle">{{ $absensi->jabatan->jabatan ?? '-' }}</td>
                            @endforeach
                        </tr>

                        <tr>
                            <td class="align-middle fw-semibold">Hasil Produksi</td>
                            @foreach ($dates as $tgl)
                            @php
                            $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                            @endphp
                            <td class="text-center align-middle">{{ $absensi->pencapaian ?? '-' }}</td>
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
                                    data-shift="{{ $absensi->shift ?? 'Pagi' }}"
                                    data-jabatan="{{ $absensi->jabatan->uuid ?? '' }}"
                                    data-grup="{{ $absensi->grup_uuid ?? '' }}"
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
    </div>

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
                        <button type="button" id="modalSaveBtn" class="btn btn-primary">Simpan Perubahan</button>
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

    currentCell = button.closest('td'); // sekarang DOM element murni

    // Set modal fields
    modal.find('#modalPegawai').val(button.dataset.pegawai);
    modal.find('#modalTanggal').val(button.dataset.tanggal);
    modal.find('#modalNama').val(button.dataset.nama);
    modal.find('#modalStatus').val(button.dataset.status || 'Masuk');
    modal.find('#modalShift').val(button.dataset.shift || 'Pagi');
    modal.find('#modalJabatan').val(button.dataset.jabatan || '');
    modal.find('#modalPencapaian').val(button.dataset.pencapaian || '');

    if (button.dataset.pencapaian) {
        modal.find('#pencapaianGroup').show();
    } else {
        modal.find('#pencapaianGroup').hide();
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
                jabatan_uuid: jabatan_uuid,
                grup_uuid: grup_uuid,
                pencapaian: pencapaian
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                currentCell.querySelector(".cell-status").textContent = status;
                currentCell.querySelector(".cell-grup").textContent = shift;
                currentCell.querySelector(".cell-jabatan").textContent =
                    document.querySelector(`#modalJabatan option[value="${jabatan_uuid}"]`)?.textContent ||
                    '-';
                currentCell.querySelector(".cell-pencapaian").textContent = pencapaian || '-';

                // Hide modal
                $('#ubahModal').modal('hide');
            } else {
                alert("Gagal simpan data: " + (data.message || ''));
            }
        })
        .catch(err => console.error("Error:", err));

});
</script>

@endsection