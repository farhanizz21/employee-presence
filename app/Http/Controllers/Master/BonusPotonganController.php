<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\BonusPotongan;
use App\Models\Master\Jabatan;

class BonusPotonganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = BonusPotongan::query();
        
        if ($request->filled('search')) {
                $search = $request->search;
                $query->where('nama', 'like', "%{$search}%");
        }

        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        
        // Urutan
        if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc'); // default sort
        }

        $bonuspotongans = $query->paginate(10)->appends($request->all());
        
        $jabatans = Jabatan::all();

        // dd($bonuspotongans);
            
        return view('master.bonuspotongan.index', compact('bonuspotongans','jabatans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('master.bonuspotongan.create', compact('jabatans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'jenis' => 'required|integer|in:1,2',
            'nama' => 'required|string|max:100',
            'nominal' => 'required|integer',
            'jabatan' => 'required|array|min:1',
            'jabatan.*' => 'uuid|exists:jabatans,uuid',
            'keterangan' => 'nullable|string',
            // 'status' => 'nullable|integer|in:1,2', // 1=Aktif, 2=Nonaktif
        ]);
        BonusPotongan::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'nama' => $validated['nama'],
            'jenis' => $validated['jenis'],
            'nominal' => $validated['nominal'],
            'keterangan' => $validated['keterangan'],
            'status' => 1, // Default status aktif
            'jabatan' => json_encode($validated['jabatan']), // simpan array ke json
            // 'jabatan' => $validated['jabatan']
            // 'created_by' => Auth::pegawai()->uuid
        ]);
        return redirect()->route('bonuspotongan.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(BonusPotongan $bonusPotongan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_non_system (string $uuid)
    {
        $bonuspotongan = BonusPotongan::where('uuid', $uuid)->firstOrFail();
        $jabatans = Jabatan::all();
        // dd($grup);
        return view('master.bonuspotongan.edit_non_system', compact('bonuspotongan','jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_non_system(Request $request, $uuid)
    {
        // dd($request->all());
        $validated = $request->validate([
            'jenis' => 'required|integer|in:1,2',
            'nama' => 'required|string|max:100',
            'nominal' => 'required|integer',
            'jabatan' => 'required|array|min:1',
            'jabatan.*' => 'uuid|exists:jabatans,uuid',
            'keterangan' => 'nullable|string',
        ]);

        $bonusPotongan = BonusPotongan::where('uuid', $uuid)->firstOrFail();
        $bonusPotongan->update($validated);

        return redirect()->route('bonuspotongan.index')->with('success', 'Data berhasil diupdate!');
    }

    public function edit_system(string $uuid)
    {
        $bonuspotongan = BonusPotongan::where('uuid', $uuid)->firstOrFail();
        $jabatans = Jabatan::all();
        // dd($grup);
        return view('master.bonuspotongan.edit_system', compact('bonuspotongan','jabatans'));
    }

    public function update_system(Request $request, $uuid)
    {
        $request->validate([
            'nominal' => 'required|numeric',
        ]);

        $bonuspotongan = BonusPotongan::where('uuid', $uuid)->firstOrFail();
        $bonusPotongan->update($validated);

        return redirect()->route('bonuspotongan.index')->with('success', 'Nominal berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BonusPotongan $bonusPotongan)
    {
        //
    }
}