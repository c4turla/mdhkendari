<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Stok;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    // Menampilkan daftar barang
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query untuk mencari barang berdasarkan nama atau barcode
        $query = Barang::query();
        
        if ($search) {
            $query->where('nama_barang', 'LIKE', "%{$search}%")
                  ->orWhere('barcode', 'LIKE', "%{$search}%");
        }
        
        // Pagination
        $barang = $query->paginate(10); // Menampilkan 10 item per halaman
        
        return view('barang.index', compact('barang'));
    }

    // Menampilkan form untuk menambah barang
    public function create()
    {
        $satuans = Satuan::all();
        return view('barang.create', compact('satuans'));
    }

    // Menyimpan barang baru ke database
    public function store(Request $request)
    {
        // Validasi input
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'barcode' => 'required|string|max:50|unique:barang,barcode',
            'keterangan' => 'required|string|max:255',
            'satuan_per_dos' => 'required|integer',
            'stok_dos' => 'required|integer',
            'stok_pcs' => 'required|integer',
            'stok_lainnya' => 'nullable|integer|min:0',
            'satuan_lainnya' => 'nullable|string|max:50',
        ]);
    
        // Simpan data ke dalam database
        Barang::create([
            'nama_barang' => $validatedData['nama_barang'],
            'barcode' => $validatedData['barcode'],
            'keterangan' => $validatedData['keterangan'],
            'satuan_per_dos' => $validatedData['satuan_per_dos'],
            'stok_dos' => $validatedData['stok_dos'],
            'stok_pcs' => $validatedData['stok_pcs'],
            'stok_lainnya' => $validatedData['stok_lainnya'] ?? 0, // Default 0 jika null
            'satuan_lainnya' => $validatedData['satuan_lainnya'] ?? null, // Default null jika tidak diisi
        ]);
    
        // Redirect atau kembalikan response
        return redirect()->route('barang.index')->with('success', 'Data Barang berhasil ditambahkan.');
    }
    

    // Menampilkan form untuk mengedit barang
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        $satuans = Satuan::all();
        return view('barang.edit', compact('barang','satuans'));
    }

    // Mengupdate data barang
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama_barang' => 'required|string|max:100',
            'barcode' => 'required|string|max:50|unique:barang,barcode,' . $id . ',id_barang',
            'keterangan' => 'required|string|max:255',
            'satuan_per_dos' => 'required|integer',
            'stok_dos' => 'required|integer',
            'stok_pcs' => 'required|integer',
            'stok_lainnya' => 'nullable|integer|min:0',
            'satuan_lainnya' => 'nullable|string|max:50',
        ]);

        $barang = Barang::findOrFail($id);
            // Update data barang
        $barang->update([
            'nama_barang' => $validatedData['nama_barang'],
            'barcode' => $validatedData['barcode'],
            'keterangan' => $validatedData['keterangan'],
            'satuan_per_dos' => $validatedData['satuan_per_dos'],
            'stok_dos' => $validatedData['stok_dos'],
            'stok_pcs' => $validatedData['stok_pcs'],
            'stok_lainnya' => $validatedData['stok_lainnya'] ?? 0, // Default 0 jika null
            'satuan_lainnya' => $validatedData['satuan_lainnya'] ?? null, // Default null jika tidak diisi
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate.');
    }

    // Menghapus barang
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
