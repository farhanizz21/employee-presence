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

        {{-- HEADER --}}
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">Review Gajian</h5>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }}
                        -
                        {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}
                    </small>
                </div>

                <div>
                    @if($periode->status == 'calculated')
                    <div class="mb-3 d-flex gap-2">
                        <form action="{{ route('gajian.recalculate', $periode->uuid) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-warning btn-recalculate">
                                <i class="fas fa-sync"></i> Proses Ulang
                            </button>
                        </form>
                        <form action="{{ route('gajian.final', $periode->uuid) }}" method="POST">
                            @csrf
                            <button type="button" class="btn btn-success btn-finalisasi">
                                <i class="fas fa-lock"></i> Finalisasi
                            </button>
                        </form>
                    </div>
                    @else
                    <span class="badge bg-success">FINAL</span>
                    <button class="btn btn-danger btn-sm" id="btnExportSelected">
                        <i class="fas fa-file-pdf"></i> Export Terpilih
                    </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark text-center">
                            <tr>
                                <th class="text-center" style="width:40px;">
                                    <input type="checkbox" id="checkAll">
                                </th>
                                <th>#</th>
                                <th>Nama</th>
                                <th style="width: 15%">
                                    <form method="GET" action="{{ route('gajian.show', $periode->uuid) }}">
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
                                        <input type="hidden" name="filter_grup" value="{{ request('filter_grup') }}">
                                    </form>
                                </th>
                                <th style="width: 15%">
                                    <form method="GET" action="{{ route('gajian.show', $periode->uuid) }}">
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
                                        <input type="hidden" name="filter_jabatan"
                                            value="{{ request('filter_jabatan') }}">
                                    </form>
                                </th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Alpha</th>
                                <th>Gaji Pokok</th>
                                <th>Bonus</th>
                                <th>Potongan</th>
                                <th>Gaji Bersih</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($gajians as $i => $gaji)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" class="check-item" value="{{ $gaji->pegawai_uuid }}">
                                </td>
                                <td class="text-center">{{ $i+1 }}</td>

                                <td>{{ $gaji->pegawai->nama ?? '-' }}</td>
                                <td>{{ $gaji->pegawai->jabatan->jabatan ?? '-' }}</td>
                                <td>{{ $gaji->pegawai->grup->nama ?? '-' }}</td>

                                <td class="text-center">{{ $gaji->hadir }}</td>
                                <td class="text-center">{{ $gaji->izin }}</td>
                                <td class="text-center">{{ $gaji->alpha }}</td>

                                <td class="text-end">
                                    Rp {{ number_format($gaji->gaji_pokok,0,',','.') }}
                                </td>

                                <td class="text-end text-success">
                                    Rp {{ number_format($gaji->bonus,0,',','.') }}
                                </td>

                                <td class="text-end text-danger">
                                    Rp {{ number_format($gaji->potongan,0,',','.') }}
                                </td>

                                <td class="text-end fw-bold">
                                    Rp {{ number_format($gaji->gaji_bersih,0,',','.') }}
                                </td>
                                @if($periode->status == 'calculated')
                                <td class="text-center">
                                    <button class="btn btn-warning btn-sm btn-edit-gaji" data-uuid="{{ $gaji->uuid }}"
                                        data-nama="{{ $gaji->pegawai->nama }}" data-bonus="{{ $gaji->bonus }}"
                                        data-potongan="{{ $gaji->potongan }}" data-toggle="modal"
                                        data-target="#modalEditGaji">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                                @endif
                                @if($periode->status == 'final')
                                <td class="text-center">
                                    {{-- 🟢 STATUS: FINAL --}}
                                    <a href="{{ route('gajian.pdf', [
                                                $periode->uuid,
                                                'pegawai_uuid' => $gaji->pegawai_uuid
                                            ]) }}" target="_blank" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                </td>
                                @endif
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Tidak ada data gajian
                                </td>
                            </tr>
                            @endforelse


                        </tbody>
                    </table>
                </div>

            </div>
            @if($periode->status == 'calculated')
            <div class="card-footer">
                <a href="{{ route('absensiUpdate.create') }}" class="btn btn-warning btn-sm">
                    <i class="fas fa-edit"></i> Edit Absen
                </a>
            </div>
            @endif
        </div>

    </div>
</div>
<!--end::App Content-->

<!-- modal -->
<div class="modal fade" id="modalEditGaji" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <form id="formEditGaji">
                @csrf
                <input type="hidden" id="gaji_uuid">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Bonus & Potongan</h5>
                    <button type="button" class="btn-close" data-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <p id="nama_pegawai" class="fw-bold"></p>

                    <div class="mb-3">
                        <label>Bonus</label>
                        <input type="number" id="bonus" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Potongan</label>
                        <input type="number" id="potongan" class="form-control">
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush


@push('scripts')
<script src="{{ asset('adminlte/plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script>
$(document).ready(function() {

    // select all
    $('#checkAll').on('change', function() {
        $('.check-item').prop('checked', $(this).prop('checked'));
    });

    $('#btnExportSelected').on('click', function() {

        let selected = [];

        $('.check-item:checked').each(function() {
            selected.push($(this).val());
        });

        if (selected.length === 0) {
            alert('Pilih minimal 1 pegawai');
            return;
        }

        let url = "{{ route('gajian.pdf', $periode->uuid) }}";

        // kirim sebagai array
        url += '?pegawai_uuid[]=' + selected.join('&pegawai_uuid[]=');

        window.open(url, '_blank');
    });

    $('.btn-recalculate').on('click', function(e) {
        e.preventDefault();

        let form = $(this).closest('form'); // ambil form parent

        Swal.fire({
            title: 'Proses ulang gaji?',
            text: 'Data gaji akan diperbarui berdasarkan absensi terbaru.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, proses ulang',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                // 🔒 disable biar tidak double klik
                $('.btn-recalculate').prop('disabled', true);

                form.submit();
            }
        });
    });

    $('.btn-finalisasi').on('click', function(e) {
        e.preventDefault();

        let form = $(this).closest('form'); // ambil form parent

        Swal.fire({
            title: 'Finalisasi gajian?',
            text: 'Data gaji akan dikunci.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, finalisasi',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {

                // 🔒 disable biar tidak double klik
                $('.btn-recalculate').prop('disabled', true);

                form.submit();
            }
        });
    });

    $('.btn-edit-gaji').on('click', function() {
        console.log('Edit Gaji clicked');
        $('#gaji_uuid').val($(this).data('uuid'));
        $('#nama_pegawai').text($(this).data('nama'));
        $('#bonus').val($(this).data('bonus'));
        $('#potongan').val($(this).data('potongan'));
    });

    $('#formEditGaji').on('submit', function(e) {
        console.log('aaaa');
        e.preventDefault();

        $.ajax({
            url: "{{ route('gajian.updateBonusPotongan') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                uuid: $('#gaji_uuid').val(),
                bonus: $('#bonus').val(),
                potongan: $('#potongan').val()
            },
            success: function(res) {
                $('#modalEditGaji').modal('hide');

                // update UI tanpa reload (optional)
                location.reload();
            }
        });
    });
});
</script>
@endpush