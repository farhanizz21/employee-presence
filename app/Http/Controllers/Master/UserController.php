<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Master\User;
use App\Models\Master\Pegawai;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('pegawai', function ($q2) use ($search) {
                        $q2->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        
        // Urutan
        if ($request->has('sort_by') && in_array($request->sort_order, ['asc', 'desc'])) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('created_at', 'desc'); // default sort
        }


        $users = $query->paginate(10)->appends($request->all());
        return view('master.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pegawais = Pegawai::all();
        return view('master.user.create', compact('pegawais'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'email' => 'nullable|email',
            'pegawai_uuid' => 'nullable|exists:pegawais,uuid',
            'role' => 'required|integer|in:1,2', // 1= Admin, 2=User
        ]);
        // dd($validated);
        User::create([
            'uuid' => \Illuminate\Support\Str::uuid(), // Generate UUID otomatis
            'username' => $validated['username'],
            'email' => $validated['email'],
            'pegawai_uuid' => $validated['pegawai_uuid'],
            'role' => $validated['role'],
            'password' => bcrypt($validated['username']),
            // 'created_by' => Auth::user()->uuid
        ]);
        return redirect()->route('user.index')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        // dd($user);
        return view('master.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'grup' => 'required|string',
            'telepon' => 'required|string|max:20',
            'jabatan' => 'required|string',
            'alamat' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->update($validated);

        return redirect()->route('user.index')->with('success', 'Data berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Data berhasil dihapus!');
    }

    // public function autocomplete(Request $request)
    // {
    //     $search = $request->get('q');
    //     $pegawais = Pegawai::where('nama', 'like', "%$search%")->limit(10)->get();
    //     return response()->json($pegawais);
    // }

    

}