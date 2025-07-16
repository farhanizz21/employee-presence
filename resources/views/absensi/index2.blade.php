@extends('layouts.app')

@section('content')
<div class="container">
    <h4>AbsensiiiPegawai</h4>

    <form method="POST" action="{{ route('absensi.store') }}">
        @csrf

        <div class="mb-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
        </div>

        <div class="mb-3">
            <label>Cari Pegawai</label>
            <input type="text" id="searchPegawai" class="form-control" placeholder="Ketik nama pegawai...">
            <small class="text-muted">Cari & pilih pegawai yang hadir. Bisa lebih dari satu.</small>
        </div>

        <div class="mt-3">
            <h6>Pegawai yang hadir:</h6>
            <ul id="listPegawai" class="list-group"></ul>
        </div>

        <input type="hidden" name="pegawai_uuids" id="pegawaiUuids">

        <div class="mt-4">
            <button type="submit" class="btn btn-success">Simpan Absensi</button>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Pegawai Belum Hadir
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Checklist</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody id="belumHadirTable">
                                @foreach ($pegawai as $p)
                                <tr>
                                    <td><input type="checkbox" name="pegawai_uuids[]" value="{{ $p->uuid }}"
                                            class="hadirCheckbox"></td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->jabatan }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        Pegawai Hadir
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                </tr>
                            </thead>
                            <tbody id="hadirTable">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

<script>
$(document).ready(function() {
    console.log("Initializing autocomplete");

    let selectedPegawai = [];

    console.log("Autocomplete script loaded");

    $('#searchPegawai').autocomplete({
        source: function(request, response) {
            console.log("Autocomplete source called with term:", request.term);

            $.ajax({
                url: "{{ route('pegawai.search') }}",
                data: {
                    term: request.term
                },
                success: function(data) {
                    console.log("Autocomplete response:", data);
                    response(data);
                },
                error: function(xhr) {
                    console.error("Error in AJAX:", xhr.status, xhr.responseText);
                }
            });
        },
        minLength: 2
    });


    // hapus dari list
    $('#listPegawai').on('click', '.btn-remove', function() {
        let uuid = $(this).data('uuid');
        selectedPegawai = selectedPegawai.filter(u => u !== uuid);
        $(this).closest('li').remove();
        $('#pegawaiUuids').val(JSON.stringify(selectedPegawai));
    });

    $('.hadirCheckbox').on('change', function() {
        let row = $(this).closest('tr');
        let clone = row.clone();
        if (this.checked) {
            $('#hadirTable').append(clone);
            row.remove();
        }
        updateHadirCount();
    });

    function updateHadirCount() {
        $('#hadirCount').text($('#hadirTable tr').length);
    }
});
</script>
@endpush