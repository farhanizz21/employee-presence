<style>
    #calendarGajian {
        max-width: 100%;
        font-size: 9px;
    }

    .fc .fc-daygrid-day-top {
        display: flex;
        justify-content: space-evenly;
    }

    /* Tinggi hari lebih pendek */
    .fc .fc-daygrid-day-frame {
        min-height: 10px;
    }

    /* Ukuran angka tanggal */
    .fc .fc-daygrid-day-number {
        font-size: 15px;
        padding: 2px;
    }

    /* Hanya bulatan kecil untuk event */
    .fc-event-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

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
            <div class="col-12 col-lg-10">
                <!--begin::Col-->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col text-start">
                                <h3 class="card-title mb-0">Data Gajian</h3>
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
                                <a href="" class="btn btn-warning">
                                    <i class="fas fa-redo"></i> Reset
                                </a>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <div class="alert alert-info mb-3">
                                <form method="GET" action="{{ route('absensi.index') }}"
                                    class="d-flex align-items-center flex-wrap gap-2 mb-0">
                                    <label for="tgl_absen" class="fw-semibold mb-0">Periode Gajian:</label>
                                    <input type="date" name="tgl_absen" id="tgl_absen"
                                        class="form-control-sm border-primary" style="max-width: 200px;"
                                        value="{{ request('tgl_absen', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                                        onchange="this.form.submit()">
                                </form>
                            </div>
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 5%">#</th>
                                        <th>Pegawai</th>
                                        <th>Grup</th>
                                        <th>Jabatan</th>
                                        <th>Total Gaji</th>
                                        <th class="text-center" style="width: 10%;">Status</th>
                                        <th class="text-center" style="width: 30%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataGaji as $index => $gaji)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $gaji['pegawai']->nama}}</td>
                                        <td>{{ $gaji['grup']->grup}}</td>
                                        <td>{{ $gaji['jabatan']->jabatan}}</td>
                                        <td><strong>Rp
                                                {{ number_format($gaji['total_gaji'], 0, ',', '.') }}</strong>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-warning">Belum Dibayar</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-success btn-detail" data-bs-toggle="modal"
                                                data-bs-target="#detailGaji" data-nama="{{ $gaji['pegawai']->nama }}"
                                                data-jabatan="{{ $gaji['jabatan']->jabatan }}"
                                                data-pokok="{{ number_format($gaji['gaji_pokok'], 0, ',', '.')  }}"
                                                data-bonus_lembur="{{ number_format($gaji['bonus_lembur'], 0, ',', '.')  }}"
                                                data-bonus_kehadiran="{{ number_format($gaji['bonus_kehadiran'], 0, ',', '.')  }}"
                                                data-potongan="{{ number_format($gaji['total_potongan'], 0, ',', '.')  }}"
                                                data-total="{{ number_format($gaji['total_gaji'], 0, ',', '.') }}"
                                                data-hadir="{{ $gaji['jumlah_hadir'] }}"
                                                data-telat="{{ $gaji['jumlah_telat'] }}"
                                                data-alpha="{{ $gaji['jumlah_alpha'] }}"
                                                data-lembur="{{ $gaji['jumlah_lembur'] }}"> <i
                                                    class="fas fa-info-circle me-2"></i>
                                                Detail
                                            </button>
                                            <button type="button" class="btn btn-sm btn-primary" disabled>
                                                <i class="fas fa-print me-1"></i> Cetak Slip Gaji
                                            </button>
                                        </td>
                                    </tr>

                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Belum ada data gajian</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="4" class="text-end">Total Keseluruhan</th>
                                        <th class="text-end fw-bold">Rp
                                            {{ number_format($totalKeseluruhan, 0, ',', '.') }}
                                        </th>
                                        <th colspan="4"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div> <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        <div class="float-end">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-2">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white py-1 text-center">
                        <i class="fas fa-calendar-alt me-1"></i> History
                    </div>
                    <div class="card-body p-1">
                        <div id="calendarGajian"></div>
                    </div>
                    <div class="mt-2 small">
                        <span class="legend-box gajian-sudah"></span> Tanggal gajian
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailGaji" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-money-bill-wave me-2"></i> Detail Gaji
                    Pegawai
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
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
                            <p class="mb-0"><strong>Periode:</strong> Januari 2025
                            </p>
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
                    <h6 class="fw-bold mb-2">Keterangan</h6>
                    <div class="mb-3">
                        <input type="text" name="keterangan" class="form-control"
                            placeholder="Tambahkan keterangan (opsional)" value="{{ old('keterangan') }}">
                        @error('keterangan')
                        <div class="invalid-feedback d-block">
                            {{ $message }}
                        </div>
                        @enderror
                        <div class="form-text">
                            Kolom isian untuk keterangan tambahan, jika ada.
                        </div>
                    </div>
                    <!-- Catatan Opsional -->
                    <div class="alert alert-warning py-2">
                        <i class="fas fa-info-circle me-2"></i>
                        Cek kembali dan pastikan nominal telah sesuai
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-1"></i> Bayar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const detailButtons = document.querySelectorAll('.btn-detail');
    const modalNama = document.getElementById('modalNama');
    const modalJabatan = document.getElementById('modalJabatan');
    const modalHadir = document.getElementById('modalHadir');
    const modalLembur = document.getElementById('modalLembur');
    const modalTelat = document.getElementById('modalTelat');
    const modalAlpha = document.getElementById('modalAlpha');
    const modalPokok = document.getElementById('modalPokok');
    const modalBonusLembur = document.getElementById('modalBonusLembur');
    const modalBonusKehadiran = document.getElementById('modalBonusKehadiran');
    const modalPotongan = document.getElementById('modalPotongan');
    const modalTotal = document.getElementById('modalTotal');

    // Hidden inputs
    const pegawaiUuidInput = document.getElementById('pegawaiUuidInput');
    const jabatanUuidInput = document.getElementById('jabatanUuidInput');
    const jumlahHadirInput = document.getElementById('jumlahHadirInput');
    const jumlahLemburInput = document.getElementById('jumlahLemburInput');
    const jumlahTelatInput = document.getElementById('jumlahTelatInput');
    const jumlahAlphaInput = document.getElementById('jumlahAlphaInput');
    const gajiPokokInput = document.getElementById('gajiPokokInput');
    const bonusLemburInput = document.getElementById('bonusLemburInput');
    const bonusKehadiranInput = document.getElementById('bonusKehadiranInput');
    const totalPotonganInput = document.getElementById('totalPotonganInput');
    const totalGajiInput = document.getElementById('totalGajiInput');

    // document.getElementById('formGaji').addEventListener('submit', function() {
    //     [gajiPokokInput, bonusLemburInput, bonusKehadiranInput, totalPotonganInput, totalGajiInput]
    //     .forEach(input => input.value = input.value.replace(/\./g, ''));
    // });


    detailButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            modalNama.textContent = this.dataset.nama;
            modalJabatan.textContent = this.dataset.jabatan;
            modalHadir.textContent = this.dataset.hadir;
            modalLembur.textContent = this.dataset.lembur;
            modalTelat.textContent = this.dataset.telat;
            modalAlpha.textContent = this.dataset.alpha;
            modalPokok.textContent = this.dataset.pokok;
            modalBonusLembur.textContent = this.dataset.bonus_lembur;
            modalBonusKehadiran.textContent = this.dataset.bonus_kehadiran;
            modalPotongan.textContent = this.dataset.potongan;
            modalTotal.textContent = this.dataset.total;

            // hidden input
            pegawaiUuidInput.value = this.dataset.pegawai_uuid;
            jabatanUuidInput.value = this.dataset.jabatan_uuid;
            jumlahHadirInput.value = this.dataset.hadir;
            jumlahLemburInput.value = this.dataset.lembur;
            jumlahTelatInput.value = this.dataset.telat;
            jumlahAlphaInput.value = this.dataset.alpha;
            gajiPokokInput.value = this.dataset.pokok;
            bonusLemburInput.value = this.dataset.bonus_lembur;
            bonusKehadiranInput.value = this.dataset.bonus_kehadiran;
            totalPotonganInput.value = this.dataset.potongan;
            totalGajiInput.value = this.dataset.total;
        });
    });

    var calendarEl = document.getElementById('calendarGajian');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 360, // Lebih kecil agar pas col-md-2
        headerToolbar: {
            left: 'prev,next',
            center: 'title',
            right: ''
        },
        events: [{
                start: '2025-08-01',
                className: 'gajian-sudah'
            },
            {
                start: '2025-08-02',
                className: 'gajian-sudah'
            },
            {
                start: '2025-08-15',
                className: 'gajian-belum'
            }
        ],
        eventContent: function(arg) {
            // Menampilkan bulatan kecil sebagai penanda gajian
            let statusClass = arg.event.classNames.includes('gajian-sudah') ? 'gajian-sudah' :
                'gajian-belum';
            return {
                html: `<span class="fc-event-dot ${statusClass}"></span>`
            };
        }
    });

    calendar.render();
});
</script>

@endpush