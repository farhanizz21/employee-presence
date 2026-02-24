<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Master\Hutang;
use App\Models\Master\Pegawai;

class HutangController extends Controller
{
    public function index(Request $request)
    {
        $query = Hutang::with('pegawai');

        // 🔹 Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->whereHas('pegawai', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%');
            });
        }

        // 🔹 Sort functionality
        if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $sort_by = $request->sort_by;
            $sort_order = $request->sort_order;
            
            if ($sort_by == 'nama') {
                $query->join('pegawais', 'hutangs.pegawai_uuid', '=', 'pegawais.uuid')
                      ->orderBy('pegawais.nama', $sort_order)
                      ->select('hutangs.*');
            } elseif ($sort_by == 'nominal') {
                $query->orderBy('hutangs.nominal', $sort_order);
            }
        } else {
            $query->orderBy('hutangs.created_at', 'desc'); // default sort
        }

        $hutangs = $query->get();
        return view('master.hutang.index', compact('hutangs'));
    }

    public function create()
    {
        $pegawais = Pegawai::all();
        return view('master.hutang.create', compact('pegawais'));
    }

    public function store(Request $request)
    {

        $request->merge([
            'nominal'  => str_replace('.', '', $request->nominal),
        ]);

        $request->validate([
            'pegawai_uuid' => 'required|exists:pegawais,uuid',
            'nominal' => 'required|numeric|min:0',
        ]);

        // dd($request->all());

        Hutang::create([
            'uuid'       => \Str::uuid(),
            'pegawai_uuid' => $request->pegawai_uuid,
            'nominal' => $request->nominal,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('hutang.index')->with('success', 'Data hutang berhasil ditambahkan');
    }

    public function edit(string $uuid)
    {
        $pegawais = Pegawai::all();
        $hutang = Hutang::where('uuid', $uuid)->firstOrFail();
        return view('master.hutang.edit', compact('hutang', 'pegawais'));
    }

    public function update(Request $request, string $uuid)
    {
        $request->merge([
            'nominal'  => str_replace('.', '', $request->nominal),
        ]);
        $request->validate([
            'pegawai_uuid' => 'required|exists:pegawais,uuid',
            'nominal' => 'required|numeric|min:0',
        ]);

        $hutang = Hutang::where('uuid', $uuid)->firstOrFail();
        $hutang->update([
            'pegawai_uuid' => $request->pegawai_uuid,
            'nominal' => $request->nominal,
            'is_active' => $request->has('is_active') ? 1 : 0,
        ]);

        return redirect()->route('hutang.index')->with('success', 'Data hutang berhasil diperbarui');
    }

    public function updateStatus(string $uuid)
    {
        $hutang = Hutang::where('uuid', $uuid)->firstOrFail();

        $hutang->update([
            'is_active' => ! $hutang->is_active
        ]);

        return redirect()
            ->route('hutang.index')
            ->with('success', 'Status hutang berhasil diperbarui.');
    }

    public function destroy(string $uuid)
    {
        $hutang = Hutang::where('uuid', $uuid)->firstOrFail();
        $hutang->delete();
        return redirect()->route('hutang.index')->with('success', 'Data hutang berhasil dihapus');
    }
}