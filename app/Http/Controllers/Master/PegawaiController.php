<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use App\Models\Master\Pegawai;
use App\Models\Master\Jabatan;
use App\Models\Master\Grup;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query = Pegawai::with(['jabatan', 'grupSb']);

        // Pencarian
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter Jabatan
        if ($request->filled('filter_jabatan')) {
            $query->where('jabatan_uuid', $request->filter_jabatan);
        }

        // Urutan
        if ($request->filled('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $pegawais = $query->paginate(10)->appends($request->all());

        $jabatans = Jabatan::all();
        $grups = Grup::all();

        return view('master.pegawai.index', compact('pegawais', 'jabatans', 'grups'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();
        $grups = Grup::all();
        return view('master.pegawai.create', compact('jabatans', 'grups'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
    'nama' => 'required|string|max:100',
    'grup' => 'required|string|in:Pagi,Malam',
    'telepon' => 'required|string|max:20',
    'jabatan' => 'required|string',
    'grup_sb' => 'required|string',
    'alamat' => 'nullable|string|max:255',
    'keterangan' => 'nullable|string|max:255',
]);

        Pegawai::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'nama' => $validated['nama'],
            'grup_uuid' => $validated['grup'],
            'grup_sb' => $validated['grup_sb'],
            'telepon' => $validated['telepon'],
            'jabatan_uuid' => $validated['jabatan'],
            'alamat' => $validated['alamat'],
            'keterangan' => $validated['keterangan']
            // 'created_by' => Auth::pegawai()->uuid
        ]);
        return redirect()->route('pegawai.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $Pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();
        return view('master.pegawai.edit', compact('Pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();
        $jabatans = Jabatan::all();
        $grups = Grup::all();
        // dd($Pegawai);
        return view('master.pegawai.edit', compact('pegawai', 'jabatans', 'grups'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:100',
        'telepon' => 'required|string|max:20',
        'grup_uuid' => 'required|string|in:Pagi,Malam', // pastikan hanya enum ini
        'jabatan_uuid' => 'required|exists:jabatans,uuid',
        'grup_sb' => 'required|exists:grups,uuid',
        'alamat' => 'nullable|string|max:255',
        'keterangan' => 'nullable|string|max:255',
    ]);

    $pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();
    $pegawai->update($validated);

    return redirect()->route('pegawai.index')->with('success', 'Data berhasil diupdate!');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $Pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();
        $Pegawai->delete();

        return redirect()->route('pegawai.index')->with('success', 'Data berhasil dihapus!');
    }

    public function search(Request $request)
    {
        $tanggal = $request->get('tgl_absen', now()->toDateString());
        $term = $request->get('term');

        $data = Pegawai::with(['jabatan'])
            ->whereDoesntHave('absensi', function ($sub) use ($tanggal) {
                $sub->whereDate('tgl_absen', $tanggal);
            })
            ->where('nama', 'like', "%$term%")
            ->take(10)
            ->get()
            ->map(function ($pegawai) {
                return [
                    'label' => $pegawai->nama,
                    'value' => $pegawai->nama,
                    'uuid' => $pegawai->uuid,
                    'grup'  => ucfirst($pegawai->grup_uuid), // langsung ambil enum
                    'grup_uuid' => $pegawai->grup_uuid,
                    'jabatan' => $pegawai->jabatan->jabatan,
                ];
            });
        return response()->json($data);
    }
}
