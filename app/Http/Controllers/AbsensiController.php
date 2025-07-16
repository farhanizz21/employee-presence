<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Master\PegawaiController;

use Illuminate\Http\Request;

use App\Models\Master\Pegawai;

class AbsensiController extends Controller
{
    public function create()
{
    $pegawai = Pegawai::orderBy('nama')->get();
    return view('absensi.index', compact('pegawai'));
}


    public function store(Request $request)
{
    $tanggal = $request->tanggal;

    $autocompleted = json_decode($request->pegawai_uuids, true) ?: [];
    $manual = $request->manual_pegawai_uuids ?: [];

    $semuaDipilih = array_unique(array_merge($autocompleted, $manual));

    foreach ($semuaDipilih as $uuid) {
        Absensi::create([
            'pegawai_uuid' => $uuid,
            'tanggal' => $tanggal,
        ]);
    }

    return redirect()->back()->with('success', 'Absensi berhasil disimpan.');
}


}