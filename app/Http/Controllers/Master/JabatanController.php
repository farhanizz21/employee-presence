<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Jabatan;
use App\Models\Master\BonusPotongan;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Jabatan::query();

        //filter sistem
        if ($request->
    filled('filter_sistem')) {
            $query->where('harian', $request->filter_sistem);
        }

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
        $BonusPotongans = BonusPotongan::all();
        return view('master.jabatan.create', compact('BonusPotongans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->merge([
            'gaji_pagi'  => str_replace('.', '', $request->gaji_pagi),
            'gaji_malam' => str_replace('.', '', $request->gaji_malam),
            'gaji_pokok' => str_replace('.', '', $request->gaji_pokok),
        ]);

        $validated = $request->validate([
            'jabatan'    => 'required|string',
            'harian'     => 'required|in:1,2',
            'gaji_pagi'  => 'nullable|numeric',
            'gaji_malam' => 'nullable|numeric',
            'gaji_pokok' => 'nullable|numeric',
            'bonus'    => 'nullable|uuid',
            'keterangan' => 'nullable|string',
        ]);

        Jabatan::create([
            'uuid'       => \Str::uuid(),
            'jabatan'    => $validated['jabatan'],
            'harian'     => $validated['harian'],
            'gaji_pagi'     => $validated['gaji_pagi'],
            'gaji_malam'     => $validated['gaji_malam'],
            'gaji_pokok'     => $validated['gaji_pokok'],
            'bonus_uuid' => $validated['bonus'] ?? null,
            'keterangan'  => $validated['keterangan'],
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil ditambahkan!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        $BonusPotongans = BonusPotongan::all();
    
        return view('master.jabatan.edit', compact('jabatan','BonusPotongans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $request->merge([
            'gaji_pagi'  => str_replace('.', '', $request->gaji_pagi),
            'gaji_malam' => str_replace('.', '', $request->gaji_malam),
            'gaji_pokok' => str_replace('.', '', $request->gaji_pokok),
        ]);


        $validated = $request->validate([
            'jabatan'    => 'required|string',
            'harian'     => 'required|in:1,2',
            'bonus'    => 'nullable',
            'gaji_pagi'  => 'nullable|numeric',
            'gaji_malam' => 'nullable|numeric',
            'gaji_pokok' => 'nullable|numeric',
            'keterangan' => 'nullable|string',
        ]);
        
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();

        $jabatan->update([
            'jabatan'    => $validated['jabatan'],
            'harian'     => $validated['harian'],
            'gaji_pagi'  => $validated['gaji_pagi'],
            'gaji_malam' => $validated['gaji_malam'],
            'gaji_pokok' => $validated['gaji_pokok'],
            'bonus_uuid' => $validated['bonus'] ?? null,
            'keterangan' => $validated['keterangan'],
        ]);

        return redirect()->route('jabatan.index')->with('success', 'Data berhasil diupdate!');
    }

    public function edit_system(string $uuid)
    {
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();
        $BonusPotongans = BonusPotongan::all();
    
        return view('master.jabatan.edit_system', compact('jabatan','BonusPotongans'));
    }

    public function update_system(Request $request, $uuid)
    {
        $request->merge([
            'gaji_pagi'  => str_replace('.', '', $request->gaji_pagi),
            'gaji_malam' => str_replace('.', '', $request->gaji_malam),
            'gaji_pokok' => str_replace('.', '', $request->gaji_pokok),
        ]);

        $validated = $request->validate([
            'gaji_pagi'  => 'nullable|numeric',
            'gaji_malam'  => 'nullable|numeric',
            'gaji_pokok'  => 'nullable|numeric',
        ]);
        
        $jabatan = Jabatan::where('uuid', $uuid)->firstOrFail();

        $jabatan->update([
            'gaji_pagi'       => $validated['gaji_pagi'],
            'gaji_malam'       => $validated['gaji_malam'],
            'gaji_pokok'       => $validated['gaji_pokok'],
        ]);

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
