<?php

namespace App\Http\Controllers;

use App\Models\Zona;
use App\Models\Depo;
use Illuminate\Http\Request;

class ZonaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil input pencarian
        
        // Jika ada input pencarian, cari berdasarkan nama atau keterangan
        if ($search) {
            $zonas = Zona::where('nama', 'like', "%{$search}%")
                        ->orWhere('keterangan', 'like', "%{$search}%")
                        ->paginate(10);
        } else {
            // Jika tidak ada input pencarian, ambil semua data
            $zonas = Zona::paginate(10);
        }
    
        return view('zonas.index', compact('zonas'));
    }

    public function create()
    {
        $depos = Depo::all();
        return view('zonas.create', compact('depos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
            'id_depo' => 'required',
        ]);

        Zona::create($request->all());

        return redirect()->route('zonas.index')
                         ->with('success', 'Zona created successfully.');
    }

    public function show(Zona $zona)
    {
        return view('zonas.show', compact('zona'));
    }

    public function edit(Zona $zona)
    {

        $depos = Depo::all();
        return view('zonas.edit', compact('zona','depos'));
    }

    public function update(Request $request, Zona $zona)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $zona->update($request->all());

        return redirect()->route('zonas.index')
                         ->with('success', 'Zona updated successfully.');
    }

    public function destroy(Zona $zona)
    {
        $zona->delete();

        return redirect()->route('zonas.index')
                         ->with('success', 'Zona deleted successfully.');
    }
}
