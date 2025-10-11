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
            {{-- Loop tiap grup_sb --}}
            @foreach($pegawaisByGrupSb as $grupSb => $pegawais)
            @php
            $namaGrup = $pegawais->first()->grupSb->nama ?? $grupSbUuid;
            $grupId = Str::slug($grupSb ?? 'tidak_diketahui');
            @endphp
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center"
                    data-bs-toggle="collapse" data-bs-target="#collapse-{{ $grupId }}" style="cursor: pointer;">
                    <h5 class="mb-0">Grup {{ $namaGrup ?? 'Tidak Diketahui' }}</h5>
                </div>

                <div id="collapse-{{ $grupId }}" class="collapse show">
                    <div class="card-body">
                        {{-- Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm table-hover paginated-table" id="table-{{ $grupId }}">
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
                                        <td class="text-center align-middle cell-status"
                                            data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">
                                            {{ $absensi->status ?? '-' }}
                                        </td>

                                        @endforeach
                                    </tr>

                                    <tr>
                                        <td class="align-middle fw-semibold">Shift</td>
                                        @foreach ($dates as $tgl)
                                        @php
                                        $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                        @endphp
                                        <td class="text-center align-middle cell-shift"
                                            data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">
                                            {{ $absensi->grup_uuid ?? '-' }}
                                        </td>

                                        @endforeach
                                    </tr>

                                    <tr>
                                        <td class="align-middle fw-semibold">Jobdesk</td>
                                        @foreach ($dates as $tgl)
                                        @php
                                        $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                        @endphp
                                        <td class="text-center align-middle cell-jobdesk"
                                            data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">
                                            {{ $absensi->jabatan->jabatan ?? '-' }}
                                        </td>

                                        @endforeach
                                    </tr>

                                    <tr>
                                        <td class="align-middle fw-semibold">Hasil Produksi</td>
                                        @foreach ($dates as $tgl)
                                        @php
                                        $absensi = $absensis[$pegawai->uuid . '_' . $tgl] ?? null;
                                        @endphp
                                        <td class="text-center align-middle cell-hasil"
                                            data-pegawai="{{ $pegawai->uuid }}" data-tanggal="{{ $tgl }}">
                                            {{ $absensi->pencapaian ?? '-' }}
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
                        {{-- Pagination container --}}
                        <div class="d-flex justify-content-center mt-2">
                            <nav>
                                <ul class="pagination pagination-sm mb-0" id="pagination-{{ $grupId }}"></ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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

    // Saat modal ditampilkan
    $('#ubahModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var modal = $(this);

        currentCell = button.closest('td'); // sel tombol edit

        modal.find('#modalPegawai').val(button.data('pegawai'));
        modal.find('#modalTanggal').val(button.data('tanggal'));
        modal.find('#modalNama').val(button.data('nama'));
        modal.find('#modalStatus').val(button.data('status') || 'Masuk');
        modal.find('#modalShift').val(button.data('shift') || 'Pagi');
        modal.find('#modalJabatan').val(button.data('jabatan') || '');
        modal.find('#modalPencapaian').val(button.data('pencapaian') || '');

        if (button.data('pencapaian')) {
            modal.find('#pencapaianGroup').show();
        } else {
            modal.find('#pencapaianGroup').hide();
        }
    });

    // Tampilkan input pencapaian hanya untuk jabatan harian == 2
    $('#modalJabatan').on('change', function() {
        const harian = $(this).find(':selected').data('harian');
        if (harian == '2') {
            $('#pencapaianGroup').show();
        } else {
            $('#pencapaianGroup').hide();
            $('#modalPencapaian').val('');
        }
    });

    // Tombol Simpan
    $('#modalSaveBtn').on('click', function() {
        if (!currentCell) return;

        let pegawai_uuid = $('#modalPegawai').val();
        let tanggal = $('#modalTanggal').val();
        let status = $('#modalStatus').val();
        let shift = $('#modalShift').val();
        let grup_uuid = shift;
        let jabatan_uuid = $('#modalJabatan').val();
        let pencapaian = $('#modalPencapaian').val();
        let jabatan_nama = $('#modalJabatan option:selected').text();

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

                    // ✅ Update langsung di tabel tanpa reload
                    const tdAksi = $(currentCell);
                    const pegawai_uuid_sel = $('#modalPegawai').val();
                    const tanggal_sel = $('#modalTanggal').val();

                    // Cari <td> berdasarkan pegawai & tanggal
                    $(`td.cell-status[data-pegawai='${pegawai_uuid_sel}'][data-tanggal='${tanggal_sel}']`).text(status);
                    $(`td.cell-shift[data-pegawai='${pegawai_uuid_sel}'][data-tanggal='${tanggal_sel}']`).text(shift);
                    $(`td.cell-jobdesk[data-pegawai='${pegawai_uuid_sel}'][data-tanggal='${tanggal_sel}']`).text(jabatan_nama || '-');
                    $(`td.cell-hasil[data-pegawai='${pegawai_uuid_sel}'][data-tanggal='${tanggal_sel}']`).text(pencapaian || '-');


                    // ✅ Tutup modal (Bootstrap 4)
                    $('#ubahModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                } else {
                    alert("Gagal simpan data: " + (data.message || ''));
                }
            })
            .catch(err => console.error("Error:", err));
    });
</script>

<script>
    // --- Pagination Lokal Tiap Grup ---
    document.addEventListener('DOMContentLoaded', function() {
        const rowsPerPage = 5; // jumlah pegawai per grup yang tampil
        document.querySelectorAll('.paginated-table').forEach(table => {
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr')).filter((r, i) => r.firstElementChild?.rowSpan);
            const totalRows = rows.length;
            const grupId = table.id.replace('table-', '');
            const pagination = document.getElementById('pagination-' + grupId);
            const totalPages = Math.ceil(totalRows / rowsPerPage);

            function showPage(page) {
                rows.forEach((row, i) => {
                    const isVisible = Math.floor(i / 1) >= (page - 1) * rowsPerPage && Math.floor(i / 1) < page * rowsPerPage;
                    for (let j = 0; j < 5; j++) {
                        const nextRow = row.parentElement.children[i * 5 + j];
                        if (nextRow) nextRow.style.display = isVisible ? '' : 'none';
                    }
                });
            }

            // buat tombol
            pagination.innerHTML = '';
            for (let i = 1; i <= totalPages; i++) {
                const li = document.createElement('li');
                li.className = 'page-item' + (i === 1 ? ' active' : '');
                li.innerHTML = `<a class="page-link" href="#">${i}</a>`;
                li.addEventListener('click', e => {
                    e.preventDefault();
                    pagination.querySelectorAll('.page-item').forEach(p => p.classList.remove('active'));
                    li.classList.add('active');
                    showPage(i);
                });
                pagination.appendChild(li);
            }

            showPage(1);
        });
    });

    // --- Animasi ikon collapse ---
    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(header => {
        const icon = header.querySelector('i');
        header.addEventListener('click', () => {
            setTimeout(() => {
                const collapseEl = document.querySelector(header.dataset.bsTarget);
                if (collapseEl.classList.contains('show')) {
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                } else {
                    icon.classList.add('fa-chevron-down');
                    icon.classList.remove('fa-chevron-up');
                }
            }, 250);
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.paginated-table tbody').forEach(tbody => {
        const rows = Array.from(tbody.querySelectorAll('tr'));
        for (let i = 0; i < rows.length; i += 5) {
            const color = (Math.floor(i / 5) % 2 === 0) ? '#393a3bff' : '#ffffff';
            for (let j = 0; j < 5; j++) {
                if (rows[i + j]) {
                    rows[i + j].style.backgroundColor = color;
                }
            }
        }
    });
});

</script>
@endsection