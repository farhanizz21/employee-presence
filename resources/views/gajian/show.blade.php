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
                    <form action="{{ route('gajian.final', $periode->uuid) }}" method="POST">
                        @csrf
                        <button class="btn btn-success"
                            onclick="return confirm('Finalisasi gajian? Data akan dikunci.')">
                            <i class="fas fa-lock"></i> Finalisasi
                        </button>
                    </form>
                    @else
                    <span class="badge bg-success">FINAL</span>
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
                                <th>#</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Grup</th>
                                <th>Hadir</th>
                                <th>Izin</th>
                                <th>Alpha</th>
                                <th>Gaji Pokok</th>
                                <th>Bonus</th>
                                <th>Potongan</th>
                                <th>Gaji Bersih</th>
                            </tr>
                        </thead>
                        <tbody>

                            @forelse($gajians as $i => $gaji)
                            <tr>
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
        </div>

    </div>
</div>
<!--end::App Content-->

@endsection