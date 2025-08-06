<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Grup;

class GrupController extends Controller
{   /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Grup::query();

        if ($request->filled('search')) {
                $search = $request->search;
                $query->where('grup', 'like', "%{$search}%");
        }
        
        // Urutan
        if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc'); // default sort
        }


        $grups = $query->paginate(10)->appends($request->all());
        return view('master.grup.index', compact('grups'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('master.grup.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'grup' => 'required|string',
        ]);
        // dd($validated);
        Grup::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'grup' => $validated['grup'],
            // 'created_by' => Auth::grup()->uuid
        ]);
        return redirect()->route('grup.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(grup $grup)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(grup $grup)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        // dd($grup);
        return view('master.grup.edit', compact('grup'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, grup $grup)
    {
        $validated = $request->validate([
            'grup' => 'required|string'
        ]);

        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        $grup->update($validated);

        return redirect()->route('grup.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(grup $grup)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        $grup->delete();

        return redirect()->route('grup.index')->with('success', 'Data berhasil dihapus!');
    }
}