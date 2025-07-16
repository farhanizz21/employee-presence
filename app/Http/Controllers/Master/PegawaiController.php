<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Pegawai;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pegawai::query();

        // Pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }
        
        // Urutan
        if ($request->has('sort_by') && $request->has('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc'); // default sort
        }

        $pegawais = $query->paginate(10)->appends($request->all());
        return view('master.pegawai.index', compact('pegawais'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.pegawai.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'golongan' => 'required|string',
            'telepon' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);
        Pegawai::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'nama' => $validated['nama'],
            'golongan_uuid' => $validated['golongan'],
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
        $Pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();
        // dd($Pegawai);
        return view('master.pegawai.edit', compact('Pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'golongan' => 'required|string',
            'telepon' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $Pegawai = Pegawai::where('uuid', $uuid)->firstOrFail();
        $Pegawai->update($validated);

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
        
        $term = $request->get('term');
        $data = Pegawai::where('nama', 'like', "%$term%")
            ->take(10)
            ->get()
            ->map(function ($pegawai) {
                return [
                    'label' => $pegawai->nama,
                    'value' => $pegawai->nama,
                    'uuid' => $pegawai->uuid,
                ];
            });
        return response()->json($data);
    }

}