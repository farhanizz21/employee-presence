<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Jabatan;
use App\Models\Master\Grup;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jabatan::query();

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('jabatan', 'like', "%{$search}%");
        }

        // Urutan
        if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc'); // default sort
        }

        $jabatans = $query->paginate(10)->appends($request->all());

        return view('master.jabatan.index', compact('jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grups = Grup::all();
        return view('master.jabatan.create', compact('grups'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'gaji_pagi'  => str_replace('.', '', $request->gaji_pagi),
            'gaji_malam' => str_replace('.', '', $request->gaji_malam),
        ]);

        $validated = $request->validate([
            'jabatan'    => 'required|string',
            'harian'     => 'required|in:1,2',
            'gaji_pagi'  => 'required|numeric',
            'gaji_malam' => 'required|numeric',
        ]);

        Jabatan::create([
            'uuid'       => \Str::uuid(),
            'jabatan'    => $validated['jabatan'],
            'harian'     => $validated['harian'],
            'gaji_pagi'  => $validated['gaji_pagi'],
            'gaji_malam' => $validated['gaji_malam'],
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil ditambahkan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        return view('master.jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $request->merge([
            'gaji_pagi'  => str_replace('.', '', $request->gaji_pagi),
            'gaji_malam' => str_replace('.', '', $request->gaji_malam),
        ]);

        $validated = $request->validate([
            'jabatan'    => 'required|string',
            'gaji_pagi'  => 'required|numeric',
            'gaji_malam' => 'required|numeric',
            'harian'     => 'required|numeric'
        ]);

        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        $jabatan->update($validated);

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil dihapus!');
    }
}
