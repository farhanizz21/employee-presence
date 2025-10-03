@extends('layouts.app')

@section('content')
<div class="container">

  <h3>Rekap Absensi Pegawai</h3>


  {{-- Filter Tanggal --}}
  <form method="GET" action="{{ route('absensi.rekap') }}" class="mb-3">
    <label>Periode:</label>
    <input type="text" name="tanggal_range" id="tanggal_range" class="form-control d-inline-block w-auto" value="{{ request('tanggal_range') }}">
    <input type="hidden" name="tanggal_mulai" id="tanggal_mulai">
    <input type="hidden" name="tanggal_selesai" id="tanggal_selesai">
    <!-- <button type="submit" class="btn btn-primary btn-sm ms-3">Tampilkan</button> -->
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
            <th class="text-center align-middle sticky-col first-col" style="min-width:150px">
              Nama Pegawai
            </th>
            <th class="text-center align-middle sticky-col second-col" style="min-width:150px">
              Default Shift
            </th>
            <th class="text-center align-middle sticky-col third-col" style="min-width:150px">
              Default Jobdesk
            </th>
            @foreach($dates as $tgl)
            <th class="text-center align-middle" style="min-width:140px">
              {{ \Carbon\Carbon::parse($tgl)->format('d M Y') }}
            </th>
            @endforeach
          </tr>
        </thead>


        <tbody>
          @foreach($pegawais as $pegawai)
          <tr>
            <td class="sticky-col first-col border">{{ $pegawai->nama }}</td>
            <td class="sticky-col second-col border">{{ $pegawai->grup_uuid ?? '-' }}</td>
            <td class="sticky-col third-col border">{{ $pegawai->jabatan->jabatan ?? '-' }}</td>
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
                value="{{ $pegawai->grup_uuid }}">
              <input type="hidden" name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][jabatan_uuid]"
                class="jabatan-input"
                value="{{ $pegawai->jabatan->uuid }}">

              {{-- Placeholder untuk info perubahan (kosong dulu, diisi via JS jika berubah) --}}
              <div class="change-info mt-1 small text-muted"></div>

              {{-- Input Pencapaian (default show jika jabatan->harian == 2) --}}
              <input type="number"
                name="absensi[{{ $pegawai->uuid }}][{{ $tgl }}][pencapaian]"
                class="form-control form-control-sm pencapaian-input"
                style="margin-top:4px; font-size:0.75rem; padding:2px 4px; {{ $pegawai->jabatan->harian == 2 ? '' : 'display:none;' }}"
                placeholder="Pencapaian">

              {{-- Tombol modal --}}
              <button type="button"
                class="btn btn-warning btn-sm mt-1 edit-btn"
                data-toggle="modal"
                data-target="#ubahModal"
                data-pegawai="{{ $pegawai->uuid }}"
                data-nama="{{ $pegawai->nama }}"
                data-tanggal="{{ $tgl }}"
                data-shift="{{ $pegawai->grup_uuid ?? '' }}"
                data-jabatan="{{ $pegawai->jabatan_uuid ?? '' }}"
                data-status="{{ $absensi[$pegawai->uuid][$tgl]['status'] ?? '' }}">
                <i class="fas fa-edit"></i>
              </button>

            </td>
            @endforeach
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <button type="submit" class="btn btn-success mt-3">
      <i class="fas fa-save fa-sm text-white-50"></i> Simpan Rekap
    </button>

    <a href="{{ route('absensi.index')}}" class="btn btn-danger shadow-sm mt-3 ms-3">
      <i class="fas fa-times fa-sm text-white-50"></i> Batal
    </a>

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
  <select id="modalGrup" class="form-control">
    @foreach(['Pagi', 'Malam'] as $shift)
      <option value="{{ $shift }}" 
        {{ $shift == $pegawai->grup_uuid ? 'selected' : '' }}>
        {{ $shift }}
      </option>
    @endforeach
  </select>
</div>

        <div class="form-group">
          <label>Jobdesk</label> <!-- jobdesk = jabatan -->
          <select id="modalJabatan" class="form-control">
            @foreach($jabatans as $jabatan)
            <option value="{{ $jabatan->uuid }}" data-harian="{{ $jabatan->harian }}">
              {{ $jabatan->jabatan }}
            </option>
            @endforeach
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
  document.addEventListener("DOMContentLoaded", () => {
    const modalPegawai = document.getElementById("modalPegawai");
    const modalTanggal = document.getElementById("modalTanggal");
    const modalNama = document.getElementById("modalNama");
    const modalGrup = document.getElementById("modalGrup");
    const modalJabatan = document.getElementById("modalJabatan");
    const modalStatus = document.getElementById("modalStatus");
    const modalPencapaianWrapper = document.createElement("div");
    const modalPencapaian = document.createElement("input");
    modalPencapaianWrapper.classList.add("form-group", "mt-2");
    modalPencapaian.type = "number";
    modalPencapaian.classList.add("form-control");
    modalPencapaian.placeholder = "Pencapaian";
    modalPencapaianWrapper.appendChild(modalPencapaian);
    // append ke modal
    document.querySelector("#ubahModal .modal-body").appendChild(modalPencapaianWrapper);
    modalPencapaianWrapper.style.display = "none";

    const modalSaveBtn = document.getElementById("modalSaveBtn");

    let targetCell = null;

    const qs = (el, sel) => el.querySelector(sel);

    function handleJabatanHarian() {
      const opt = modalJabatan.options[modalJabatan.selectedIndex];
      const harian = opt.getAttribute("data-harian");

      // Tampilkan/Hide input pencapaian di modal
      if (harian === "2") {
        modalPencapaianWrapper.style.display = "block";
        // isi nilai pencapaian dari cell jika ada
        const pencapaianInput = qs(targetCell, ".pencapaian-input");
        modalPencapaian.value = pencapaianInput ? pencapaianInput.value : '';
      } else {
        modalPencapaianWrapper.style.display = "none";
        modalPencapaian.value = '';
      }
    }

    document.querySelectorAll(".edit-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        targetCell = btn.closest("td");
        modalPegawai.value = btn.dataset.pegawai;
        modalTanggal.value = btn.dataset.tanggal;
        modalNama.value = btn.dataset.nama;
        modalGrup.value = btn.dataset.shift;
        modalJabatan.value = btn.dataset.jabatan; // <-- update sesuai dataset
        modalStatus.value = qs(targetCell, ".status-select").value;

        handleJabatanHarian();
      });
    });

    modalJabatan.addEventListener("change", handleJabatanHarian);

    modalSaveBtn.addEventListener("click", () => {
      const hiddenShift = qs(targetCell, ".shift-input");
      const hiddenJabatan = qs(targetCell, ".jabatan-input");
      const statusSelect = qs(targetCell, ".status-select");
      const pencapaianInput = qs(targetCell, ".pencapaian-input");
      const infoBox = qs(targetCell, ".change-info");

      const grupText = modalGrup.options[modalGrup.selectedIndex]?.text || '';
      const jabatanText = modalJabatan.options[modalJabatan.selectedIndex]?.text || '';
      const pencapaianVal = modalPencapaianWrapper.style.display === "block" ? modalPencapaian.value : '';

      const changes = [];

      // update hidden shift & jabatan
      if (hiddenShift && hiddenShift.value !== modalGrup.value) {
        hiddenShift.value = modalGrup.value;
        changes.push(`Shift: <span class="text-primary">${grupText}</span>`);
      }
      if (hiddenJabatan && hiddenJabatan.value !== modalJabatan.value) {
        hiddenJabatan.value = modalJabatan.value;
        changes.push(`Jobdesk: <span class="text-primary">${jabatanText}</span>`);
      }

      // update status select
      if (statusSelect) statusSelect.value = modalStatus.value;

      // update pencapaian di cell
      if (pencapaianInput) {
        if (modalPencapaianWrapper.style.display === "block") {
          pencapaianInput.style.display = "block";
          pencapaianInput.value = pencapaianVal;
        } else {
          pencapaianInput.style.display = "none";
          pencapaianInput.value = '';
        }
      }

      // tampilkan info perubahan
      if (infoBox) {
        infoBox.innerHTML = changes.length ?
          `<div class="badge bg-warning text-dark">${changes.join(' | ')}</div>` :
          '';
      }

      $("#ubahModal").modal("hide");
    });
  });
</script>

<script>
  $(function() {
    $('#tanggal_range').daterangepicker({
      locale: {
        format: 'YYYY-MM-DD'
      },
      autoUpdateInput: false
    });

    $('#tanggal_range').on('apply.daterangepicker', function(ev, picker) {
      $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
      $('#tanggal_mulai').val(picker.startDate.format('YYYY-MM-DD'));
      $('#tanggal_selesai').val(picker.endDate.format('YYYY-MM-DD'));

      // langsung submit form filter
      $(this).closest("form").submit();
    });
  });
</script>

<style>
  /* semua kolom sticky */
  .sticky-col {
    position: sticky;
    background: #fff;
    z-index: 2;
  }

  /* Kolom pertama */
  .first-col {
    left: 0;
    z-index: 3;
    box-shadow: 2px 0 0 #dee2e6 inset;
    /* garis kanan */
    border-right: 1px solid #dee2e6;
    /* backup border */
  }

  /* Kolom kedua */
  .second-col {
    left: 150px;
    z-index: 3;
    box-shadow: 2px 0 0 #dee2e6 inset;
    border-right: 1px solid #dee2e6;
  }

  /* Kolom ketiga */
  .third-col {
    left: 300px;
    z-index: 3;
    box-shadow: 2px 0 0 #dee2e6 inset;
    border-right: 1px solid #dee2e6;
  }

  /* biar border bawah tetap ada di tbody */
  .table-bordered tbody td {
    border: 1px solid #dee2e6 !important;
  }

  /* header gelap tetap rapi */
  .table-dark .sticky-col {
    background: #343a40;
    color: #fff;
  }
</style>

@endsection