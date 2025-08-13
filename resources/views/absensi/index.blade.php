<style>
    #pegawai-search.loading {
        background-image: url('/spinner.gif');
        background-repeat: no-repeat;
        background-position: right center;
        background-size: 20px 20px;
    }

    .select2-container--bootstrap-5 .select2-selection--single {
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: .875rem;
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
                <div class="alert alert-info shadow-sm mb-4">
                    <form method="GET" action="{{ route('absensi.index') }}"
                        class="d-flex align-items-center flex-wrap gap-2 mb-0">
                        <label for="tgl_absen" class="fw-semibold mb-0">Tanggal Absensi:</label>
                        <input type="date" name="tgl_absen" id="tgl_absen" class="form-control-sm border-primary"
                            style="max-width: 200px;"
                            value="{{ request('tgl_absen', \Carbon\Carbon::today()->format('Y-m-d')) }}"
                            onchange="this.form.submit()">
                    </form>
                </div>

                <small class="text-muted">Gunakan <strong> tabel </strong> di bawah untuk absen berdasarkan <strong>
                        grup.</strong>
                </small>

                <!-- Daftar Pegawai -->
                <div class="card mb-3">
                    <div class="card-header">
                        <div
                            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <h3 class="card-title mb-0">Daftar Grup</h3>
                            <div class="w-100 w-md-auto" style="max-width: 350px;">
                                <div class="input-group input-group-sm shadow-sm">
                                    <input type="text" id="search-by-grup" class="form-control"
                                        placeholder="Cari grup…">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="clearSearch()">×</button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="table-responsive" id="accordionGrup">
                        <table class="table table-bordered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Aksi</th>
                                    <th style="width: 10%; text-align: center;">No</th>
                                    <th style="width: 70%;">Grup</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($grups as $grup)
                                <tr>
                                    <td>
                                        <button class="btn btn-outline-primary btn-sm toggle-arrow"
                                            data-target="#collapse{{ $loop->iteration }}" aria-expanded="false"
                                            aria-controls="collapse{{ $loop->iteration }}">
                                            <i class="fas fa-chevron-down me-1"></i> Tampilkan Detail
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>{{ $grup->grup }}</td>
                                </tr>

                                <!-- collapse grup data pegawai -->
                                <tr>
                                    <td colspan="3" class="p-0">
                                        <div id="collapse{{ $loop->iteration }}" class="collapse bg-light"
                                            data-bs-parent="#accordionGrup">
                                            <div class="card border border-secondary m-2">
                                                <div class="card-body p-3">
                                                    <form class="user" method="post"
                                                        action="{{ route('absensi.store') }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-bordered mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width: 10%;">Check</th>
                                                                        <th style="width: 5%;">#</th>
                                                                        <th>Nama Pegawai</th>
                                                                        <th style="width: 20%;">Jabatan</th>
                                                                        <th style="width: 20%;">Aksi</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <input type="hidden" name="grup_uuid"
                                                                        value="{{ $grup->uuid }}">
                                                                    <input type="hidden" name="tgl_absen"
                                                                        class="tgl_absen_hidden">
                                                                    @forelse ($grup->pegawai as $pegawai)
                                                                    <input type="hidden" name="jabatan_uuid"
                                                                        class="jabatan_uuid_hidden"
                                                                        value="{{ $pegawai->jabatan->uuid }}">
                                                                    <tr>
                                                                        <td>
                                                                            <input class="form-check-input"
                                                                                type="checkbox" name="pegawai[]"
                                                                                value="{{ $pegawai->uuid }}"
                                                                                class="form-check-input" checked>
                                                                        </td>
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td class="td-nama"> {{ $pegawai->nama }}</td>
                                                                        <td class="td-jabatan">
                                                                            {{ $pegawai->jabatan->jabatan }}</td>
                                                                        <input type="hidden" name="jabatan_uuid[]"
                                                                            class="jabatan-hidden"
                                                                            value="{{ $pegawai->jabatan->uuid }}">
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-warning btn-ganti-pegawai"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalPilihPengganti"
                                                                                data-pegawai="{{ $pegawai->uuid }}"
                                                                                data-grup="{{ $grup->uuid }}"
                                                                                data-tgl="{{ $tanggal }}"
                                                                                title="Ganti Pegawai">
                                                                                <i class="fas fa-sync-alt"></i>
                                                                            </button>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-info btn-ganti-jabatan"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#modalGantiJabatan"
                                                                                data-pegawai="{{ $pegawai->uuid }}"
                                                                                data-grup="{{ $grup->uuid }}"
                                                                                data-tgl="{{ $tanggal }}"
                                                                                title="Ganti Jabatan">
                                                                                <i class="fas fa-briefcase"></i>
                                                                            </button>

                                                                        </td>

                                                                    </tr>
                                                                    @empty
                                                                    <tr>
                                                                        <td colspan="5" class="text-center">Tidak ada
                                                                            data
                                                                        </td>
                                                                    </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                            <div class="mt-2">
                                                                <small>Data yang dipilih:</small>
                                                                <button type="submit" name="status" value="1"
                                                                    class="btn btn-success btn-sm">
                                                                    <i class="fas fa-check-circle"></i> Hadir
                                                                </button>
                                                                <button type="submit" name="status" value="2"
                                                                    class="btn btn-primary btn-sm">
                                                                    <i class="fas fa-business-time"></i> Lembur
                                                                </button>
                                                                <button type="submit" name="status" value="3"
                                                                    class="btn btn-warning btn-sm">
                                                                    <i class="fas fa-clock"></i> Telat
                                                                </button>
                                                                <button type="submit" name="status" value="4"
                                                                    class="btn btn-danger btn-sm">
                                                                    <i class="fas fa-user-times"></i> Alpha
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No Data</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <small class="text-muted">Gunakan <strong>pencarian </strong> di bawah untuk absen <strong> per
                        pegawai.</strong></small>
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Cari Pegawai</h3>
                    </div>


                    {{-- Search Box --}}
                    <form class="user" method="post" action="{{ route('absensi.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="tgl_absen" class="tgl_absen_hidden">
                        <input type="hidden" name="grup_uuid" class="grup_uuid_hidden">

                        <div class="row g-3 align-items-center justify-content-center">
                            <div class="col-md-6 mb-3">
                                <select class="form-select" data-role="pegawai-select" name="pegawai">
                                    {{-- Options diisi lewat JS --}}
                                </select>
                            </div>
                            <div class="col-auto d-flex flex-wrap gap-2">
                                {{-- Hadir --}}
                                <button type="submit" name="status" value="1" class="btn btn-success">
                                    <i class="fas fa-check-circle me-1"></i> Hadir
                                </button>

                                {{-- Lembur --}}
                                <button type="submit" name="status" value="2" class="btn btn-primary">
                                    <i class="fas fa-business-time me-1"></i> Lembur
                                </button>

                                {{-- Telat --}}
                                <button type="submit" name="status" value="3" class="btn btn-warning text-dark">
                                    <i class="fas fa-clock me-1"></i> Telat
                                </button>

                                {{-- Alpha --}}
                                <button type="submit" name="status" value="4" class="btn btn-danger">
                                    <i class="fas fa-user-times me-1"></i> Alpha
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>

            <!-- KANAN -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header border-bottom">
                        <div class="row w-100">
                            <div class="col-6">
                                <h5 class="card-title mb-0">Riwayat</h5>
                            </div>
                            <div class="col-6 text-end text-muted small">
                                Tanggal : {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <!-- Hadir -->
                        <div class="p-2">
                            <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                                <table class="table table-striped table-sm mt-2">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Grup</th>
                                            <th>Nama</th>
                                            <th>Jabatan</th>
                                            <th style="width: 20%;">Status</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @forelse($riwayats as $index => $riwayat)
                                            <td>{{ $riwayat->pegawai->grup->grup}}</td>
                                            <td>{{ $riwayat->pegawai->nama}}</td>
                                            <td>{{ $riwayat->jabatan->jabatan}}</td>
                                            <td>
                                                @php
                                                $statusList = [
                                                1 => ['label' => 'Hadir', 'class' => 'success'],
                                                2 => ['label' => 'Lembur', 'class' => 'primary'],
                                                3 => ['label' => 'Telat', 'class' => 'warning'],
                                                4 => ['label' => 'Alfa', 'class' => 'danger'],
                                                ];
                                                $status = $statusList[$riwayat->status] ?? ['label' => 'Unknown',
                                                'class'
                                                => 'secondary'];
                                                @endphp
                                                <span class="badge bg-{{ $status['class'] }}">
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada data absensi untuk
                                                tanggal ini.</td>
                                        </tr>
                                        @endforelse
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

<!-- Modal Pilih Pengganti -->
<div class="modal fade" id="modalPilihPengganti" tabindex="-1" aria-labelledby="modalPilihPenggantiLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="" method="GET" class="text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPilihPenggantiLabel">
                        Pilih Pegawai Pengganti
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning small">
                        Pegawai ini tidak hadir. Silakan pilih pegawai pengganti dari grup lain yang tersedia. Hanya
                        pegawai yang belum absen hari ini yang akan muncul.
                    </div>
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Pilih Pegawai Pengganti</label>
                        <input type="hidden" id="pegawai_lama_uuid">
                        <input type="hidden" id="grup_uuid">
                        <select class="form-control" data-role="pegawai-select" id="pegawai_pengganti"></select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Ganti Jabatan -->
<div class="modal fade" id="modalGantiJabatan" tabindex="-1" aria-labelledby="modalGantiJabatanLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formGantiJabatan" class="text-center">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGantiJabatanLabel">Ganti Jabatan Sementara</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 text-start">
                        <label class="form-label fw-bold">Pilih Jabatan</label>
                        <input type="hidden" id="jab_pegawai_uuid">
                        <input type="hidden" id="jab_grup_uuid">
                        <input type="hidden" id="jab_tgl_absen">
                        <select class="form-control" id="jabatan_pengganti">
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->uuid }}">{{ $jabatan->jabatan }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>


@endsection

@push('scripts')
<script>
    function clearSearch() {
    // console.log("Resetting search input");
    const searchInput = document.getElementById("search-by-grup");
    searchInput.value = "";
    searchInput.dispatchEvent(new Event("input")); // Trigger filter reset
}

$(document).ready(function() {

    //search pegawai
    $('[data-role="pegawai-select"]').each(function() {
        const $this = $(this);
        const isInModal = $this.closest('.modal').length > 0;

        $this.select2({
            placeholder: 'Cari nama pegawai…',
            minimumInputLength: 2,
            allowClear: true,
            theme: 'bootstrap-5',
            dropdownParent: isInModal ? $this.closest('.modal') : $(document.body),
            ajax: {
                url: '{{ route("pegawai.search") }}',
                dataType: 'json',
                delay: 250,
                data: params => ({
                    term: params.term,
                    tgl_absen: document.getElementById('tgl_absen').value
                }),
                processResults: data => {
                    // console.log("Response dari server:", data);
                    return {
                        results: data.map(item => ({
                            id: item.uuid,
                            text: item.label,
                            grup: item.grup,
                            grup_uuid: item.grup_uuid,
                            jabatan: item.jabatan,
                            jabatan_uuid: item.jabatan_uuid
                        }))
                    };
                },
                cache: true
            },
            templateResult: formatPegawaiOption,
            templateSelection: formatPegawaiSelection,
            language: {
                inputTooShort: () => 'Ketik minimal 2 huruf…',
                noResults: () => 'Tidak ditemukan / Sudah absen',
                searching: () => 'Mencari…'
            }
        });

        function formatPegawaiOption(item) {
            if (item.loading) return item.text;

            return $(`
            <div>
                <div><strong>${item.text}</strong></div>
                <div class="small text-muted">
                    Grup: ${item.grup || '-'}<br>
                    Jabatan: ${item.jabatan || '-'}
                </div>
            </div>
        `);
        }

        function formatPegawaiSelection(item) {
            return item.text || item.id;
        }
    });

    $('[data-role="pegawai-select"]').on('select2:select', function(e) {
        const data = e.params.data;
        $(this).closest('form').find('.grup_uuid_hidden').val(data.grup_uuid || '');
        $(this).closest('form').find('.jabatan_uuid_hidden').val(data.jabatan_uuid || '');
    });

    // Fokus slelect2
    $('[data-role="pegawai-select"]').on('select2:open', () => {
        setTimeout(() => {
            document.querySelector(
                '.select2-container--open .select2-search__field').focus();
        }, 0);
    });

    //modal
    $('.modal').on('shown.bs.modal', function() {
        $(this).find('[data-role="pegawai-select"]').select2('open');
    });

    // collapse
    $('.toggle-arrow').on('click', function() {
        const icon = $(this).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-up');
    });

    const toggles = document.querySelectorAll(".toggle-arrow");
    toggles.forEach(function(btn) {
        btn.addEventListener("click", function(e) {
            const targetId = btn.getAttribute("data-target");
            const target = document.querySelector(targetId);
            const allCollapses = document.querySelectorAll(".collapse");
            // Jika sudah terbuka, tutup saja
            if (target.classList.contains("show")) {
                const collapse = bootstrap.Collapse.getInstance(target) || new bootstrap
                    .Collapse(target, {
                        toggle: false
                    });
                collapse.hide();
                return;
            }
            // Tutup semua collapse lain
            allCollapses.forEach(function(col) {
                if (col !== target && col.classList.contains("show")) {
                    const other = bootstrap.Collapse.getInstance(col) || new bootstrap
                        .Collapse(col, {
                            toggle: false
                        });
                    other.hide();
                }
            });
            // Buka target
            const thisCollapse = bootstrap.Collapse.getInstance(target) || new bootstrap
                .Collapse(target, {
                    toggle: false
                });
            thisCollapse.show();
        });
    });

    // Filter grup berdasarkan input
    const searchInput = document.getElementById("search-by-grup");
    const tableRows = document.querySelectorAll("table tbody tr:not(.collapse)");

    searchInput.addEventListener("input", function() {
        const keyword = this.value.toLowerCase();

        tableRows.forEach((row) => {
            const namaGrup = row.cells[1]?.textContent.toLowerCase() || "";
            const isMatch = namaGrup.includes(keyword);
            row.style.display = isMatch ? "" : "none";
            // Tampilkan/ubah juga baris collapse terkait (baris setelahnya)
            const collapseRow = row.nextElementSibling;
            if (collapseRow && collapseRow.classList.contains("collapse")) {
                collapseRow.style.display = isMatch ? "" : "none";
            }
        });
    });

    //isi tanggal
    document.querySelectorAll('form.user').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            const globalTanggal = document.getElementById('tgl_absen').value;
            if (!globalTanggal) {
                e.preventDefault();
                alert('Tanggal absensi harus dipilih!');
                return;
            }
            form.querySelector('input.tgl_absen_hidden').value = globalTanggal;
        });

    });

    // Ganti pegawai
    let pegawaiLama = '';
    let grupUuid = '';
    let tgl_absen = '';

    $(document).on('click', '.btn-ganti-pegawai', function() {
        pegawaiLama = $(this).data('pegawai');
        grupUuid = $(this).data('grup');
        tgl_absen = $(this).data('tgl');
    });

    // Event select pegawai pengganti
    $('#pegawai_pengganti').on('select2:select', function(e) {
        const penggantiUuid = e.params.data.id;

        $.ajax({
            url: "{{ route('absensi.gantiPegawai') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pegawai_lama: pegawaiLama,
                pegawai_baru: penggantiUuid,
                grup_uuid: grupUuid,
                tgl_absen: tgl_absen
            },
            success: function(response) {
                $('#modalPilihPengganti').modal('hide');

                const row = $(`button[data-pegawai="${pegawaiLama}"]`).closest('tr');
                row.find('td:nth-child(3)').text(response.nama_baru);
                row.find('td:nth-child(4)').text(response.jabatan_baru);

                alert('Pegawai berhasil diganti!');
                location.reload();
            },
            error: function(xhr) {
                console.error('Error Ajax:', xhr.status, xhr.responseText);
                alert('Gagal menyimpan pengganti!');
            }
        });
    });

    // Event select jabatan
        let jabpegawaiUuid, jabgrupUuid, jabtglAbsen;

    // Event click button ganti jabatan
    $(document).on('click', '.btn-ganti-jabatan', function() {
        jabpegawaiUuid = $(this).data('pegawai');
        jabgrupUuid = $(this).data('grup');
        jabtglAbsen = $(this).data('tgl');

        $('#jab_pegawai_uuid').val(jabpegawaiUuid);
        $('#jab_grup_uuid').val(jabgrupUuid);
        $('#jab_tgl_absen').val(jabtglAbsen);
    });

    // Submit form ganti jabatan
    $('#formGantiJabatan').on('submit', function(e) {
        e.preventDefault();
        const jabatanBaru = $('#jabatan_pengganti').val();

        $.ajax({
            url: "{{ route('absensi.gantiJabatan') }}",
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                pegawai_uuid: jabpegawaiUuid,
                grup_uuid: jabgrupUuid,
                tgl_absen: jabtglAbsen,
                jabatan_uuid: jabatanBaru
            },
            success: function(response) {
                $('#modalGantiJabatan').modal('hide');

                const row = $(`button[data-pegawai="${jabpegawaiUuid}"]`).closest('tr');
                row.find('td:nth-child(4)').text(response.jabatan_baru);

                alert('Jabatan sementara berhasil diganti!');
            },
            error: function(xhr) {
                console.error('Error Ajax:', xhr.status, xhr.responseText);
                alert('Gagal mengganti jabatan!');
            }
        });
    });

});
</script>
@endpush