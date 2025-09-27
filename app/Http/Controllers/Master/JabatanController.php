<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Jabatan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jabatan::query();

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
        return view('master.jabatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->merge([
            'gaji' => str_replace('.', '', $request->gaji),
        ]);

        $validated = $request->validate([
            'jabatan' => 'required|string',
            'gaji' => 'required|numeric',
            'harian' => 'required|numeric',
        ]);
        // dd($validated);
        Jabatan::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'jabatan' => $validated['jabatan'],
            'gaji' => $validated['gaji'],
            'harian' => $validated['harian'],
            // 'created_by' => Auth::jabatan()->uuid
        ]);
        return redirect()->route('jabatan.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jabatan $jabatan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(String $uuid)
    {
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        // dd($jabatan);
        return view('master.jabatan.edit', compact('jabatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $uuid)
    {
        $request->merge([
        'gaji' => str_replace('.', '', $request->gaji), // ubah "85.000" jadi "85000"
    ]);
        $validated = $request->validate([
            'jabatan' => 'required|string',
            'gaji' => 'required|numeric',
            'harian' => 'required|numeric'
        ]);

        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        $jabatan->update($validated);

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $uuid)
    {
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        $jabatan->delete();

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil dihapus!');
    }
}