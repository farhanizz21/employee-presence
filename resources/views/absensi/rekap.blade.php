@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Rekap Absensi Pegawai</h3>

    {{-- Filter Tanggal --}}
    <form method="GET" action="{{ route('absensi.rekap') }}" class="mb-3">
        <label>Dari:</label>
        <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}">
        <label>Sampai:</label>
        <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
        <button type="submit" class="btn btn-primary btn-sm">Tampilkan</button>
    </form>

    {{-- Form Input Absensi --}}
    <form action="{{ route('absensi.simpanRekap') }}" method="POST">
        @csrf
        <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
<input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">

        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Pegawai</th>
                        <th>Shift Default</th>
                        <th>Jobdesk Default</th>
                        @foreach($dates as $tgl)
                        <th>{{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($pegawais as $pegawai)
                    <tr>
                        <td>{{ $pegawai->nama }}</td>
                        <td>{{ $pegawai->default_shift ?? 'Pagi' }}</td>
                        <td>{{ $pegawai->grup->grup ?? '-' }}</td>

                        @foreach($dates as $tgl)
                        <td>
                            {{-- Select Status --}}
                            <select name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][status]"
                                class="form-select form-select-sm status-select"
                                style="font-size:0.75rem; padding:2px 4px;">
                                <option value="Alpha">Alpha</option>
                                <option value="Masuk">Masuk</option>
                                <option value="Izin">Izin</option>
                                <option value="Telat">Telat</option>
                                <option value="Lembur">Lembur</option>
                            </select>

                            {{-- Hidden shift & grup (default) --}}
                            <input type="hidden" name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][shift]"
                                   class="shift-input"
                                   value="{{ $pegawai->default_shift ?? 'Pagi' }}">
                            <input type="hidden" name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][grup_uuid]"
                                   class="grup-input"
                                   value="{{ $pegawai->grup_uuid ?? '' }}">

                            {{-- Tombol modal --}}
                            <button type="button"
                                    class="btn btn-warning btn-sm mt-1 edit-btn"
                                    data-toggle="modal"
                                    data-target="#ubahModal"
                                    data-pegawai="{{ $pegawai->uuid }}"
                                    data-nama="{{ $pegawai->nama }}"
                                    data-tanggal="{{ $tgl }}"
                                    data-shift="{{ $pegawai->default_shift ?? 'Pagi' }}"
                                    data-grup="{{ $pegawai->grup_uuid ?? '' }}"
                                    data-status="Masuk">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn btn-success mt-3">Simpan Rekap</button>
    </form>
</div>

{{-- Modal --}}
<div class="modal fade" id="ubahModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
          <h5 class="modal-title">Ubah Shift & Jobdesk</h5>
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
      </div>
      <div class="modal-footer">
          <button type="button" id="modalSaveBtn" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    let targetCell = null;

    // buka modal
    document.querySelectorAll(".edit-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            targetCell = this.closest("td");
            document.getElementById("modalPegawai").value = this.dataset.pegawai;
            document.getElementById("modalTanggal").value = this.dataset.tanggal;
            document.getElementById("modalNama").value = this.dataset.nama;
            document.getElementById("modalShift").value = this.dataset.shift;
            document.getElementById("modalGrup").value = this.dataset.grup;
            document.getElementById("modalStatus").value = targetCell.querySelector("select").value;
        });
    });

    // simpan ke hidden input
    document.getElementById("modalSaveBtn").addEventListener("click", function () {
        if (targetCell) {
            let shiftInput = targetCell.querySelector(".shift-input");
            let grupInput = targetCell.querySelector(".grup-input");
            let statusSelect = targetCell.querySelector(".status-select");

            shiftInput.value = document.getElementById("modalShift").value;
            grupInput.value = document.getElementById("modalGrup").value;
            statusSelect.value = document.getElementById("modalStatus").value;
        }
        $("#ubahModal").modal("hide");
    });
});
</script>
@endsection
