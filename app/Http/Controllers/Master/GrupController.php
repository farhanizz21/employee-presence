<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Grup;

class GrupController extends Controller
{
    public function index(Request $request)
    {
        $query = Grup::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('grup', 'like', "%{$search}%");
        }

        if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $grups = $query->paginate(10)->appends($request->all());

        return view('master.grup.index', compact('grups'));
    }

    public function create()
    {
        return view('master.grup.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'grup' => 'required|string',
        ]);

        Grup::create([
            'uuid' => \Illuminate\Support\Str::uuid(),
            'grup' => $validated['grup'],
        ]);

        return redirect()->route('grup.index')->with('success', 'Data berhasil ditambahkan!');
    }

    public function show(Grup $grup)
    {
        //
    }

    public function edit(String $uuid)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        return view('master.grup.edit', compact('grup'));
    }

    public function update(Request $request, string $uuid)
    {
        $validated = $request->validate([
            'grup' => 'required|string'
        ]);

        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        $grup->update($validated);

        return redirect()->route('grup.index')->with('success', 'Data berhasil diupdate!');
    }

    public function destroy(String $uuid)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        $grup->delete();

        return redirect()->route('grup.index')->with('success', 'Data berhasil dihapus!');
    }
}
