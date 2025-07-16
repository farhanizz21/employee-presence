<style>
#pegawai-search.loading {
    background-image: url('/spinner.gif');
    background-repeat: no-repeat;
    background-position: right center;
    background-size: 20px 20px;
}
</style>

@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Row-->
        <div class="row">
            <div class="col-sm-6">
                <h3 class="mb-0 fw-bold">Absensi Pegawai</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Absensi Pegawai
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
        <!-- Info boxes -->
        <div class="row">
            <!-- KIRI -->
            <div class="col-md-8">
                <div class="text-center mb-3">
                    <p class="text-muted mb-1">Gunakan <strong>search box</strong> untuk perorangan</p>
                </div>

                {{-- Search Box --}}
                <form action="" method="GET" class="mb-3 text-center">
                    <div class="input-group input-group-lg mx-auto" style="max-width: 600px;">
                        <select id="pegawai-select" name="pegawai"></select>
                        <button type="submit" class="btn btn-success rounded-pill ms-2 px-3 py-1 fs-6">
                            <i class="fas fa-check-circle"></i> Hadir
                        </button>
                        <button type="submit" class="btn btn-danger rounded-pill ms-2 px-3 py-1 fs-6">
                            <i class="fas fa-times-circle"></i> Izin
                        </button>
                        <button type="submit" class="btn btn-warning rounded-pill ms-2 px-3 py-1 fs-6">
                            <i class="fas fa-ambulance"></i> Sakit
                        </button>
                    </div>
                </form>

                <p class="text-center text-muted mb-3">atau gunakan <strong>tabel</strong> di bawah untuk grup</p>

                <!-- Daftar Pegawai -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Pegawai</h3>
                    </div>
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-striped table-sm mt-2 align-middle">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 50px;" class="text-center">#</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                                <tr>
                                    <td class="text-center"><input type="checkbox" /></td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <div>
                            <input type="checkbox" id="checkAll" />
                            <label for="checkAll" class="mb-0">Pilih Semua</label>
                        </div>
                        <a href="#" class="btn btn-sm btn-warning">
                            <i class="fas fa-plus"></i> Tambah Daftar Antrian
                        </a>
                    </div>
                </div>


                <!-- Antrian -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Antrian</h3>
                    </div>
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-striped table-sm mt-2">
                            <thead class="table-light sticky-top">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                    <td><span class="badge text-bg-warning">Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                    <td><span class="badge text-bg-warning">Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                    <td><span class="badge text-bg-warning">Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                    <td><span class="badge text-bg-warning">Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                    <td><span class="badge text-bg-warning">Menunggu</span></td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Bejo</td>
                                    <td>Staff</td>
                                    <td><span class="badge text-bg-warning">Menunggu</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2">
                        <a href="#" class="btn btn-sm btn-success"><i class="fas fa-check-circle"></i> Hadir</a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="fas fa-times-circle"></i> Izin</a>
                        <a href="#" class="btn btn-sm btn-warning"><i class="fas fa-ambulance"></i> Sakit</a>
                    </div>
                </div>
            </div>

            <!-- KANAN -->
            <div class="col-md-4">
                <!-- Widget waktu -->
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <small>Waktu Sekarang </small>
                        <div id="clock" class="fs-4 fw-bold mt-2"></div>
                        <div id="date" class="text-muted"></div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header border-bottom">
                        <h3 class="card-title">Riwayat Hari Ini</h3>
                    </div>
                    <div class="card-body p-0">
                        <!-- Hadir -->
                        <div class="p-2 border-bottom">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Hadir</strong>
                                <span class="badge bg-success">1</span>
                            </div>
                            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                <table class="table table-striped table-sm mt-2">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Jam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <!-- Tambahkan data lain jika ada -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- Izin -->
                        <div class="p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>Izin</strong>
                                <span class="badge bg-danger">1</span>
                            </div>
                            <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                                <table class="table table-striped table-sm mt-2">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th>Jam</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <tr>
                                            <td>Bejo</td>
                                            <td>Staff</td>
                                            <td>10.00 WIB</td>
                                        </tr>
                                        <!-- Tambahkan data lain jika ada -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateClock() {
    const now = new Date();
    const jam = now.getHours().toString().padStart(2, '0');
    const menit = now.getMinutes().toString().padStart(2, '0');
    const detik = now.getSeconds().toString().padStart(2, '0');
    const hari = now.toLocaleDateString('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });

    document.getElementById('clock').textContent = `${jam}:${menit}:${detik} WIB`;
    document.getElementById('date').textContent = hari;
}
setInterval(updateClock, 1000);
updateClock();

$(document).ready(function() {
    $('#pegawai-select').select2({
        placeholder: 'Cari nama pegawai…',
        minimumInputLength: 2,
        allowClear: true,
        theme: 'bootstrap-5',
        ajax: {
            url: '{{ route("pegawai.search") }}',
            dataType: 'json',
            delay: 250,
            data: params => ({
                term: params.term
            }),
            processResults: data => ({
                results: data.map(item => ({
                    id: item.uuid,
                    text: item.label
                }))
            }),
            cache: true
        },
        language: {
            inputTooShort: () => 'Ketik minimal 2 huruf…',
            noResults: () => 'Tidak ditemukan',
            searching: () => 'Mencari…'
        }
    });


    $('#pegawai-select').on('select2:open', () => {
        setTimeout(() => {
            document.querySelector('.select2-container--open .select2-search__field').focus();
        }, 0);
    });


});
</script>
@endpush