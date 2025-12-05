    <style>
/* Warna sesuai status */
.gajian-sudah {
    background-color: #28a745;
}

.gajian-belum {
    background-color: #ffc107;
}

/* Legend kotak kecil */
.legend-box {
    display: inline-block;
    width: 12px;
    height: 12px;
    margin-left: 10px;
    margin-right: 5px;
    border-radius: 50%;
}

.legend-box.gajian-sudah {
    background-color: #28a745;
}

table thead.bg-info th {
    background-color: #3d4041ff !important;
    color: #fff !important;
}
    </style>

    @extends('layouts.app')

    @section('content')

    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
                <div class="col-sm-6">
                    <h3 class="mb-0">Gajian</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-end">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Gajian
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
            <div class="row g-3 align-items-stretch">
                <!-- tabel gajian -->
                <div class="col-12 col-lg-12">
                    <!--begin::Col-->
                    <div class="card mb-4">
                        <div class="alert alert-warning d-flex align-items-center mb-4" role="alert">
                            <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                            <strong>Perhatian&nbsp;:&nbsp;</strong>Data yang sudah berstatus
                            <strong>&nbsp;Sudah Gajian&nbsp;</strong> tidak dapat diubah lagi.
                            Pastikan seluruh absensi sudah final sebelum melakukan proses pembayaran.
                        </div>

                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col text-start">
                                    <form method="GET" action="{{ route('gajian.index') }}"
                                        class="d-flex align-items-center flex-wrap gap-2 mb-0">
                                        <label for="periode_uuid" class="fw-semibold mb-0">Periode Gajian:</label>
                                        <select name="periode_uuid" id="periode_uuid"
                                            class="form-select form-select-sm border-primary" style="max-width: 250px;"
                                            onchange="this.form.submit()">
                                            @foreach($periodes as $periode)
                                            <option value="{{ $periode->uuid }}"
                                                {{ $periodeUuid == $periode->uuid ? 'selected' : '' }}>
                                                {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                                                -
                                                {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="" class="row gy-2 gx-3 mb-3 align-items-center">
                                <div class="col-md-6 col-lg-4">
                                    <input type="text" name="search" class="form-control" placeholder="Cari…" value="">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-auto">
                                    <a href="{{ route('gajian.index') }}" class="btn btn-warning">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-sm table-hover">
                                    <thead class="bg-info text-center">
                                        <tr>
                                            <th style="width: 5%">#</th>
                                            <th>Pegawai</th>
                                            <th>Grup</th>
                                            <th>Shift</th>
                                            <th>Jabatan</th>
                                            <th>Total Gaji</th>
                                            <th class="text-center" style="width: 10%;">Status</th>
                                            <th class="text-center" style="width: 30%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($semua_gajian as $index => $gaji)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $gaji['pegawai']->nama}}</td>
                                            <td>{{ $gaji['pegawai']->grupSb->nama}}</td>
                                            <td>{{ $gaji['pegawai']->grup_uuid}}</td>
                                            <td>{{ $gaji['jabatan']->jabatan}}</td>
                                            <td><strong>Rp
                                                    {{ number_format($gaji['total_gaji'], 0, ',', '.') }}</strong>
                                            </td>
                                            <td class="text-center">
                                                @if($gaji['status_gajian'] == '1')
                                                <span class="badge bg-success">Sudah Gajian</span>
                                                @else
                                                <span class="badge bg-warning text-dark">Belum Gajian</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-success btn-detail" type="button"
                                                    data-toggle="modal" data-target="#detailGaji"
                                                    data-nama="{{ $gaji['pegawai']->nama }}"
                                                    data-pegawai_uuid="{{ $gaji['pegawai']->uuid }}"
                                                    data-jabatan="{{ $gaji['jabatan']->jabatan }}"
                                                    data-jabatan_uuid="{{ $gaji['jabatan']->uuid }}"
                                                    data-pokok="{{ number_format($gaji['gaji_pokok'], 0, ',', '.') }}"
                                                    data-bonus_lembur="{{ number_format($gaji['bonus_lembur'], 0, ',', '.') }}"
                                                    data-bonus_kehadiran="{{ number_format($gaji['bonus_kehadiran'], 0, ',', '.') }}"
                                                    data-potongan="{{ number_format($gaji['total_potongan'], 0, ',', '.') }}"
                                                    data-total="{{ number_format($gaji['total_gaji'], 0, ',', '.') }}"
                                                    data-hadir="{{ $gaji['jumlah_hadir'] }}"
                                                    data-telat="{{ $gaji['jumlah_telat'] }}"
                                                    data-alpha="{{ $gaji['jumlah_alpha'] }}"
                                                    data-lembur="{{ $gaji['jumlah_lembur'] }}"
                                                    data-periode_mulai="{{ $gaji['periode_mulai'] }}"
                                                    data-periode_selesai="{{ $gaji['periode_selesai'] }}"
                                                    data-status="{{ $gaji['status_gajian'] }}"
                                                    data-detail='@json($gaji["detail_absensi"], JSON_HEX_APOS | JSON_HEX_QUOT)'>
                                                    <i class="fas fa-info-circle me-2"></i> Detail
                                                </button>


                                                <!-- <button type="button" class="btn btn-sm btn-primary"
                                                    {{ $gaji['status_gajian'] != '1' ? 'disabled' : '' }}>
                                                    <i class="fas fa-print me-1"></i> Cetak Slip Gaji
                                                </button> -->
                                                @if($gaji['status_gajian'] == '1')
                                                <a href="{{ route('gajian.cetak', $gaji['uuid']) }}" target="_blank"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-print me-1"></i> Cetak Slip Gaji
                                                </a>
                                                @endif


                                            </td>

                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="8" class="text-center">Belum ada data gajian</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                    <!-- <tfoot class="table-light">
                                        <tr>
                                            <th colspan="4" class="text-end">Total Keseluruhan</th>
                                            <th class="text-end fw-bold">Rp
                                            </th>
                                            <th colspan="4"></th>
                                        </tr>
                                    </tfoot> -->
                                </table>
                            </div>
                        </div> <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <div class="float-end">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="detailGaji" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white position-relative">
                    <h5 class="modal-title mb-0">
                        <i class="fas fa-money-bill-wave me-2"></i> Detail Gaji Pegawai
                    </h5>
                    <button type="button" class="close text-white position-absolute bg-danger" style="right: 15px;"
                        data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form id="formGaji" class="gajian" method="post" action="{{ route('gajian.store') }}">
                    @csrf
                    <div class="modal-body">
                        <!-- Informasi Pegawai -->
                        <div class="row mb-3">
                            <div class="col-md-9">
                                <h5 class="mb-1" id="modalNama" value=""></h5>
                                <input type="hidden" name="pegawai_uuid" id="pegawaiUuidInput">
                                <p class="mb-0"><strong>Jabatan : </strong> <span id="modalJabatan"></span></p>
                                <input type="hidden" name="jabatan_uuid" id="jabatanUuidInput">
                                <input type="hidden" name="periode_uuid" value="{{ $periodeUuid }}">
                            </div>
                        </div>
                        <hr>
                        <h6 class="fw-bold mb-2">Rekap Kehadiran</h6>
                        <table class="table table-sm table-bordered mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Keterangan</th>
                                    <th class="text-center">Jumlah Hari</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Hadir</td>
                                    <td class="text-center" id="modalHadir"></td>
                                    <input type="hidden" name="jumlah_hadir" id="jumlahHadirInput">
                                </tr>
                                <tr>
                                    <td>Lembur</td>
                                    <td class="text-center" id="modalLembur"></td>
                                    <input type="hidden" name="jumlah_lembur" id="jumlahLemburInput">
                                </tr>
                                <tr>
                                    <td>Terlambat</td>
                                    <td class="text-center" id="modalTelat"></td>
                                    <input type="hidden" name="jumlah_telat" id="jumlahTelatInput">
                                </tr>
                                <tr>
                                    <td>Alpha</td>
                                    <td class="text-center" id="modalAlpha"></td>
                                    <input type="hidden" name="jumlah_alpha" id="jumlahAlphaInput">
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <!-- Rincian Gaji -->
                        <h6 class="fw-bold mb-2">Rincian Gaji</h6>
                        <table class="table table-sm table-bordered mb-3">
                            <thead class="table-light">
                                <tr>
                                    <th>Deskripsi</th>
                                    <th class="text-end">Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Gaji Pokok</td>
                                    <td class="text-end" id="modalPokok"></td>
                                    <input type="hidden" name="gaji_pokok" id="gajiPokokInput">
                                </tr>
                                <tr>
                                    <td>Bonus Lembur</td>
                                    <td class="text-end" id="modalBonusLembur"></td>
                                    <input type="hidden" name="bonus_lembur" id="bonusLemburInput">
                                </tr>
                                <tr>
                                    <td>Bonus Kehadiran</td>
                                    <td class="text-end" id="modalBonusKehadiran"></td>
                                    <input type="hidden" name="bonus_kehadiran" id="bonusKehadiranInput">
                                </tr>
                                <tr>
                                    <td class="text-danger">Potongan Terlambat</td>
                                    <td class="text-end text-danger" id="modalPotongan">-</td>
                                    <input type="hidden" name="total_potongan" id="totalPotonganInput">
                                </tr>
                                <tr class="table-primary fw-bold">
                                    <td>Total Gaji Diterima</td>
                                    <td class="text-end" id="modalTotal"></td>
                                    <input type="hidden" name="total_gaji" id="totalGajiInput">
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <h6 class="fw-bold mb-2">Rincian Absensi Periode</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Jabatan</th>
                                        <th>Shift</th>
                                        <th>Gaji Harian</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="modalDetailAbsensi">
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div id="periodeInfo"
                            class="alert alert-warning py-2 px-3 small d-flex align-items-center mt-2 mb-0"
                            role="alert">
                            <i class="fas fa-exclamation-circle me-2 text-warning"></i>
                            <div>
                                Perhitungan absensi dilakukan dari tanggal
                                <span id="modalPeriodeMulai" class="fw-semibold text-dark"></span>
                                sampai
                                <span id="modalPeriodeSelesai" class="fw-semibold text-dark"></span>
                                <br>
                                <strong>Pastikan nominal telah sesuai</strong>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times me-1"></i> Tutup
                        </button>
                        @if (!isset($gaji['status_gajian']) || $gaji['status_gajian'] != 1)
                        <button type="submit" class="btn btn-success" id="btnBayar">
                            <i class="fas fa-check-circle me-1"></i> Bayar
                        </button>
                        @endif

                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.btn-detail');
    detailButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Header & hidden inputs
            document.getElementById('modalNama').textContent = this.dataset.nama;
            document.getElementById('modalJabatan').textContent = this.dataset.jabatan;
            document.getElementById('pegawaiUuidInput').value = this.dataset.pegawai_uuid;
            document.getElementById('jabatanUuidInput').value = this.dataset.jabatan_uuid;

            document.getElementById('modalHadir').textContent = this.dataset.hadir;
            document.getElementById('modalLembur').textContent = this.dataset.lembur;
            document.getElementById('modalTelat').textContent = this.dataset.telat;
            document.getElementById('modalAlpha').textContent = this.dataset.alpha;
            document.getElementById('jumlahHadirInput').value = this.dataset.hadir;
            document.getElementById('jumlahLemburInput').value = this.dataset.lembur;
            document.getElementById('jumlahTelatInput').value = this.dataset.telat;
            document.getElementById('jumlahAlphaInput').value = this.dataset.alpha;

            document.getElementById('modalPokok').textContent = this.dataset.pokok;
            document.getElementById('modalBonusLembur').textContent = this.dataset.bonus_lembur;
            document.getElementById('modalBonusKehadiran').textContent = this.dataset
                .bonus_kehadiran;
            document.getElementById('modalPotongan').textContent = this.dataset.potongan;
            document.getElementById('modalTotal').textContent = this.dataset.total;
            document.getElementById('gajiPokokInput').value = this.dataset.pokok;
            document.getElementById('bonusLemburInput').value = this.dataset.bonus_lembur;
            document.getElementById('bonusKehadiranInput').value = this.dataset.bonus_kehadiran;
            document.getElementById('totalPotonganInput').value = this.dataset.potongan;
            document.getElementById('totalGajiInput').value = this.dataset.total;

            // Periode
            const hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jum'at", "Sabtu"];
            const bulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli",
                "Agustus", "September", "Oktober", "November", "Desember"
            ];

            const periodeMulai = new Date(this.dataset.periode_mulai + 'T00:00:00');
            const periodeSelesai = new Date(this.dataset.periode_selesai + 'T00:00:00');

            // Format tanggal Indonesia pakai Intl.DateTimeFormat
            const options = {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            };
            const tanggalMulai = new Intl.DateTimeFormat('id-ID', options).format(periodeMulai);
            const tanggalSelesai = new Intl.DateTimeFormat('id-ID', options).format(
                periodeSelesai);

            // Gabungkan HARI + TANGGAL
            document.getElementById('modalPeriodeMulai').textContent =
                hari[periodeMulai.getDay()] + ", " + tanggalMulai;

            document.getElementById('modalPeriodeSelesai').textContent =
                hari[periodeSelesai.getDay()] + ", " + tanggalSelesai;


            // Detail Absensi
            let detailAbsensi = [];
            try {
                detailAbsensi = JSON.parse(this.dataset.detail || '[]');
            } catch (e) {
                detailAbsensi = [];
                console.error('JSON detail_absensi error', e);
            }

            const modalDetailAbsensi = document.getElementById('modalDetailAbsensi');
            modalDetailAbsensi.innerHTML = ''; // reset

            if (detailAbsensi.length > 0) {
                detailAbsensi.forEach(item => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `<td>
                    ${new Intl.DateTimeFormat('id-ID', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    }).format(new Date(item.tanggal))}
                    </td>
            <td>${item.jabatan}</td><td>${item.grup_uuid}</td>
            <td class="text-end">Rp ${parseInt(item.gaji).toLocaleString('id-ID')}</td>
            <td class="text-center">${item.status}</td>
        `;
                    modalDetailAbsensi.appendChild(tr);
                });
            } else {
                modalDetailAbsensi.innerHTML = `
        <tr>
            <td colspan="4" class="text-center text-muted">Tidak ada data</td>
        </tr>
    `;
            }
        });
    });
});
    </script>
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.btn-detail');
    const btnBayar = document.getElementById('btnBayar');

    detailButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // isi data modal seperti biasa ...
            document.getElementById('modalNama').textContent = this.dataset.nama;
            // dst... (semua pengisian lain tidak perlu diubah)

            // Tampilkan / sembunyikan tombol Bayar sesuai status_gajian
            if (this.dataset.status == '1') {
                btnBayar.style.display = 'none';
            } else {
                btnBayar.style.display = 'inline-block';
            }
        });
    });
});
    </script>
    @endpush