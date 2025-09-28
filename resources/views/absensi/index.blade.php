@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Absensi Pegawai</h3>

    {{-- Filter --}}
    <form method="GET" action="{{ route('absensi.index') }}" class="mb-3">
  <label>Pilih Periode:</label>
  <select name="periode_uuid" onchange="this.form.submit()" class="form-control" style="max-width: 250px; display:inline-block;">
      @foreach($periodes as $p)
          <option value="{{ $p->uuid }}" {{ $periodeUuid == $p->uuid ? 'selected' : '' }}>
              {{ \Carbon\Carbon::parse($p->tanggal_mulai)->format('d M Y') }} 
              - {{ \Carbon\Carbon::parse($p->tanggal_selesai)->format('d M Y') }}
          </option>
      @endforeach
  </select>
</form>

  <div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
    <tr>
        <th>No.</th>
        <th>Nama Karyawan</th>
        <th>Ket.</th>
        @foreach ($dates as $tgl)
            <th colspan="2">
                {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
            </th>
        @endforeach
    </tr>
    <tr>
        <th
    colspan="3">Hasil Produksi</th>
    @foreach ($dates as $tgl)
        <th>P : {{ $hasilProduksi[$tgl]->hasil_pagi ?? '-' }}</th>
        <th>M : {{ $hasilProduksi[$tgl]->hasil_malam ?? '-' }}</th>
    @endforeach
    </tr>
</thead>
        <tbody>
            @foreach($pegawais as $i => $pegawai)
            <tr>
                <td>{{ $i+1 }}</td>
                    <td>{{ $pegawai->nama }}</td>
                    <td>
                        <div><strong>Status</strong></div>
                        <div><strong>Shift</strong></div>
                        <div><strong>Jobdesk</strong></div>
                        <div><strong>Hasil Produksi</strong></div>
                        <div><strong>Aksi</strong></div>
                    </td>
                @foreach($dates as $tgl)
                @php
                $absensi = $absensis[$pegawai->uuid.'_'.$tgl] ?? null;
                @endphp
                    <td colspan="2">
                            <div class="cell-status">{{ $absensi->status ?? '-' }}</div>
                            <div class="cell-shift">{{ $absensi->grup->grup ?? '-' }}</div>
                            <div class="cell-grup">{{ $absensi->jabatan->jabatan ?? '-' }}</div>
                            <div class="cell-grup">{{ $absensi->pencapaian ?? '-' }}</div>
                            <div>
                                <button class="btn btn-sm btn-primary edit-btn"
                        data-pegawai="{{ $pegawai->uuid }}"
                        data-tanggal="{{ $tgl }}"
                        data-nama="{{ $pegawai->nama }}"
                        data-status="{{ $absensi->status ?? '' }}"
                        data-shift="{{ $absensi->shift ?? '' }}"
                        data-grup="{{ $absensi->grup_uuid ?? '' }}">
                        Edit
                    </button></div>
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>

    </table>
    {{-- Modal Ubah --}}
    <div class="modal fade" id="ubahModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <form id="modalForm" onsubmit="return false;">
            <div class="modal-header">
              <h5 class="modal-title">Ubah Absensi</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
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
        <select id="modalGrup" class="form-control">
          @foreach($grups as $grup)
          <option value="{{ $grup->uuid }}">{{ $grup->grup }}</option>
          @endforeach
      </select>
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
</div>
<script>
    let currentCell = null;

// buka modal saat klik tombol edit
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            currentCell = this.closest("td");

            document.getElementById("modalPegawai").value = this.dataset.pegawai;
            document.getElementById("modalTanggal").value = this.dataset.tanggal;
            document.getElementById("modalNama").value    = this.dataset.nama;

            document.getElementById("modalStatus").value = this.dataset.status || 'Masuk';
            document.getElementById("modalShift").value  = this.dataset.shift || 'Pagi';
            document.getElementById("modalGrup").value   = this.dataset.grup || '';

        $('#ubahModal').modal('show'); // tampilkan modal
    });
    });

// simpan perubahan
    document.getElementById("modalSaveBtn").addEventListener("click", function () {
        if (!currentCell) return;

        let pegawai_uuid = document.getElementById("modalPegawai").value;
        let tanggal      = document.getElementById("modalTanggal").value;
        let status       = document.getElementById("modalStatus").value;
        let shift        = document.getElementById("modalShift").value;
        let grup         = document.getElementById("modalGrup").value;

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
                grup_uuid: grup
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
            // update tampilan cell
                currentCell.querySelector(".cell-status").textContent = status;
                currentCell.querySelector(".cell-shift").textContent  = shift;
                currentCell.querySelector(".cell-grup").textContent   =
                document.querySelector(`#modalGrup option[value="${grup}"]`)?.textContent || '';

                $('#ubahModal').modal('hide');
            } else {
                alert("Gagal simpan data: " + (data.message || ''));
            }
        })
        .catch(err => console.error("Error:", err));
    });
</script>

@endsection
