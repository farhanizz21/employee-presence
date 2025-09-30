@extends('layouts.app')
@section('content')
<div class="container">
  <h3>Preview Gajian — Periode: {{ \Carbon\Carbon::parse($periode->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($periode->tanggal_selesai)->format('d M Y') }}</h3>

  <form action="{{ route('gajian.saveDraft', $periode->uuid) }}" method="POST">
    @csrf
    <table class="table table-sm">
      <thead><tr><th>Nama</th><th>Hadir</th><th>Gaji Pokok</th><th>Bonus Lembur</th><th>Bonus Kehadiran</th><th>Potongan</th><th>Total</th><th>Pilih</th></tr></thead>
      <tbody>
        @foreach($drafts as $d)
          <tr>
            <td>{{ $d['pegawai']->nama }}</td>
            <td>{{ $d['jumlah_hadir'] }}</td>
            <td>{{ number_format($d['gaji_pokok'] ?? 0,0,',','.') }}</td>
            <td>{{ number_format($d['bonus_lembur'] ?? 0,0,',','.') }}</td>
            <td>{{ number_format($d['bonus_kehadiran'] ?? 0,0,',','.') }}</td>
            <td>{{ number_format($d['total_potongan'] ?? 0,0,',','.') }}</td>
            <td>{{ number_format($d['total_gaji'] ?? 0,0,',','.') }}</td>
            <td>
              <input type="checkbox" name="rows[{{ $loop->index }}][selected]" checked hidden>
              <input type="hidden" name="rows[{{ $loop->index }}][pegawai_uuid]" value="{{ $d['pegawai']->uuid }}">
              <input type="hidden" name="rows[{{ $loop->index }}][gaji_pokok]" value="{{ $d['gaji_pokok'] }}">
              <input type="hidden" name="rows[{{ $loop->index }}][bonus_lembur]" value="{{ $d['bonus_lembur'] }}">
              <input type="hidden" name="rows[{{ $loop->index }}][bonus_kehadiran]" value="{{ $d['bonus_kehadiran'] }}">
              <input type="hidden" name="rows[{{ $loop->index }}][total_potongan]" value="{{ $d['total_potongan'] }}">
              <input type="hidden" name="rows[{{ $loop->index }}][total_gaji]" value="{{ $d['total_gaji'] }}">
              <input type="hidden" name="rows[{{ $loop->index }}][uuid]" value="{{ $d['uuid'] ?? '' }}">
              <input type="hidden" name="rows[{{ $loop->index }}][pegawai][uuid]" value="{{ $d['pegawai']->uuid }}">
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <button class="btn btn-primary" type="submit">Save Draft</button>
    <button formaction="{{ route('gajian.finalize', $periode->uuid) }}" formmethod="POST" class="btn btn-success" onclick="return confirm('Finalisasi payroll?')">
      @csrf @method('POST') Finalize
    </button>

  </form>
</div>
@endsection
