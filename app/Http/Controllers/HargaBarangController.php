<?php

namespace App\Http\Controllers;

use App\Models\HargaBarang;
use App\Models\Zona;
use App\Models\Barang;
use Illuminate\Http\Request;

class HargaBarangController extends Controller
{
    public function index()
    {
        $hargaBarang = HargaBarang::with('barang', 'zona')->paginate(10);
        return view('harga_barang.index', compact('hargaBarang'));
    }

    public function create()
    {
        $barang = Barang::all();
        $zona = Zona::all();
        return view('harga_barang.create', compact('barang', 'zona'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id_barang',
            'zona_id' => 'required|exists:zonas,id_zona',
            'harga_per_dos' => 'required|numeric|min:0',
            'harga_per_pcs' => 'required|numeric|min:0',
        ]);

        HargaBarang::create($request->all());

        return redirect()->route('harga_barang.index')->with('success', 'Harga Barang berhasil ditambahkan');
    }

    public function edit($id)
    {
        $hargaBarang = HargaBarang::findOrFail($id);
        $barang = Barang::all();
        $zona = Zona::all();
        return view('harga_barang.edit', compact('hargaBarang', 'barang', 'zona'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id_barang',
            'zona_id' => 'required|exists:zonas,id_zona',
            'harga_per_dos' => 'required|numeric|min:0',
            'harga_per_pcs' => 'required|numeric|min:0',
        ]);

        $hargaBarang = HargaBarang::findOrFail($id);
        $hargaBarang->update($request->all());

        return redirect()->route('harga_barang.index')->with('success', 'Harga Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        $hargaBarang = HargaBarang::findOrFail($id);
        $hargaBarang->delete();

        return redirect()->route('harga_barang.index')->with('success', 'Harga Barang berhasil dihapus');
    }
}
