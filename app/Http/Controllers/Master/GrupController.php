<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Master\Grup;

class GrupController extends Controller
{
    public function index(Request $request)
    {
        $query = Grup::query();

        // 🔍 Filter pencarian
        if ($request->has('search') && $request->search != '') {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        // ↕️ Sorting
        $sortBy = $request->get('sort_by', 'nama');
        $sortOrder = $request->get('sort_order', 'asc');

        $query->orderBy($sortBy, $sortOrder);

        // 📄 Pagination
        $grups = $query->paginate(10);

        return view('master.grup.index', compact('grups'));
    }

    public function create()
    {
        return view('master.grup.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
        ]);

        Grup::create([
            'uuid' => Str::uuid(),
            'nama' => $validated['nama'],
        ]);

        return redirect()->route('grup.index')->with('success', 'Data grup berhasil ditambahkan.');
    }

    public function edit($uuid)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        return view('master.grup.edit', compact('grup'));
    }

    public function update(Request $request, $uuid)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:50',
        ]);

        $grup->update($validated);

        return redirect()->route('grup.index')->with('success', 'Data grup berhasil diperbarui.');
    }

    public function destroy($uuid)
    {
        $grup = Grup::where('uuid', $uuid)->firstOrFail();
        $grup->delete(); // ← ini sekarang soft delete, bukan hard delete

        return redirect()->route('grup.index')->with('success', 'Data grup berhasil dihapus (soft delete).');
    }
}
