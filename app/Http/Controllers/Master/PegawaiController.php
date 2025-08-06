<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\Pegawai;
use App\Models\Master\Grup;
use App\Models\Master\Jabatan;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
{
    $query = Pegawai::query();

    // Pencarian
    if ($request->filled('search')) {
        $query->where('nama', 'like', '%' . $request->search . '%');
    }

    // Filter Jabatan
    if ($request->filled('filter_jabatan')) {
        $query->where('jabatan_uuid', $request->filter_jabatan);
    }

    // Filter Grup
    if ($request->filled('filter_grup')) {
        $query->where('grup_uuid', $request->filter_grup);
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

    // public function index(Request $request)
    // {
    //     $query = Pegawai::query();

    //     // Pencarian
    //     if ($request->has('search') && $request->search != '') {
    //         $query->where('nama', 'like', '%' . $request->search . '%');
    //     }
        
    //     // Urutan
    //     if ($request->has('sort_by') && $request->has('sort_order')) {
    //         $query->orderBy($request->sort_by, $request->sort_order);
    //     } else {
    //         $query->orderBy('created_at', 'desc'); // default sort
    //     }
        
    //     $pegawais = $query->paginate(10)->appends($request->all());

    //     //filter jabatan
    //     if ($request->filled('filter_jabatan')) {
    //         $query->where('uuid', $request->filter_jabatan);
    //     }

    //     //filter grup
    //     if ($request->filled('filter_grup')) {
    //         $query->where('uuid', $request->filter_grup);
    //     }
        

    //     $jabatans = Jabatan::all();
    //     $grups = Grup::all();

    //     return view('master.pegawai.index', compact('pegawais', 'jabatans','grups'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $grups = Grup::all();
        $jabatans = Jabatan::all();
        return view('master.pegawai.create', compact('jabatans', 'grups'));
    } 

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'grup' => 'required|string',
            'telepon' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);
        Pegawai::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'nama' => $validated['nama'],
            'grup_uuid' => $validated['grup'],
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
            'grup_uuid' => 'required|string',
            'jabatan_uuid' => 'required|string',
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
        $tanggal = $request->get('tgl_absen', now()->toDateString());
        $term = $request->get('term');
        
        $data = Pegawai::with(['grup', 'jabatan'])
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
                    'grup'  => $pegawai->grup->grup,
                    'grup_uuid' => $pegawai->grup->uuid,
                    'jabatan' => $pegawai->jabatan->jabatan,
                ];
            });
        return response()->json($data);
    }

    

}