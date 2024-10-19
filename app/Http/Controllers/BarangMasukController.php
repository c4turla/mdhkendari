<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Satuan;
use App\Models\Depo;
use App\Models\DetailBarangMasuk;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    // Menampilkan daftar barang masuk
    public function index(Request $request)
    {
        // Ambil nilai pencarian dari request
        $search = $request->input('search');
        
        // Query untuk mengambil barang masuk dengan pencarian dan pagination
        $barangMasuk = BarangMasuk::with('detailBarangMasuk', 'depo')
            ->when($search, function ($query, $search) {
                return $query->where('kode_barang_masuk', 'like', "%{$search}%")
                             ->orWhere('keterangan', 'like', "%{$search}%");
            })
            ->orderByDesc('created_at')
            ->paginate(10); // Atur jumlah item per halaman
            $depos = Depo::all();
    
        // Mengirimkan data ke view dengan pencarian
        return view('barang_masuk.index', compact('barangMasuk','depos'));
    }

 
    // Menampilkan form untuk input barang masuk baru
    public function create()
    {
        // Mengambil data zona untuk ditampilkan pada dropdown
        $depos = Depo::all();
        $barangs = Barang::all();
        $satuans = Satuan::all();
        $kode = BarangMasuk::generateKode();
        // Mengirimkan data ke view create
        return view('barang_masuk.create', compact('depos','kode','barangs','satuans'));
    }

    // Menyimpan data barang masuk
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_barang_masuk' => 'required',
            'tanggal_masuk' => 'required|date',
            'id_depo' => 'required|exists:depos,id_depo',
            'keterangan' => 'nullable',
            'barang' => 'required|array',
            'barang.*' => 'required|exists:barang,id_barang',
            'jumlah_dos' => 'required|array',
            'jumlah_dos.*' => 'required|numeric',
            'jumlah_pcs' => 'required|array',
            'jumlah_pcs.*' => 'required|numeric',
            'jumlah_lainnya' => 'nullable|array',
            'jumlah_lainnya.*' => 'nullable|numeric',
            'satuan_lainnya' => 'nullable|array',
            'satuan_lainnya.*' => 'nullable|string',
        ]);
    
        // Simpan data barang masuk
        $barangMasuk = BarangMasuk::create([
            'kode_barang_masuk' => $request->kode_barang_masuk,
            'tanggal_masuk' => $request->tanggal_masuk,
            'id_depo' => $request->id_depo,
            'keterangan' => $request->keterangan,
        ]);
    
        // Simpan data detail barang masuk dan update stok barang
        $detailBarangMasuk = [];
        for ($i = 0; $i < count($request->barang); $i++) {
            $detailBarangMasuk[] = new DetailBarangMasuk([
                'id_barang' => $request->barang[$i],
                'jumlah_dos' => $request->jumlah_dos[$i],
                'jumlah_pcs' => $request->jumlah_pcs[$i],
                'jumlah_lainnya' => $request->jumlah_lainnya[$i],
                'satuan_lainnya' => $request->satuan_lainnya[$i],
                'id_masuk' => $barangMasuk->id_masuk,
            ]);
    
            // Update stok barang di tabel barang
            $barang = Barang::find($request->barang[$i]);
            if ($barang) {
                $barang->stok_dos += $request->jumlah_dos[$i];
                $barang->stok_pcs += $request->jumlah_pcs[$i];
                $barang->stok_lainnya += $request->jumlah_lainnya[$i];
                $barang->save(); // Simpan perubahan stok
            }
        }
        // Simpan banyak detail barang masuk
        $barangMasuk->detailBarangMasuk()->saveMany($detailBarangMasuk);
    
        // Redirect atau kembalikan respons sesuai kebutuhan
        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil ditambahkan dan stok diperbarui.');
    }
    

    // Menampilkan detail barang masuk berdasarkan ID
    public function show($id)
    {
        // Eager load 'detailBarangMasuk' and the related 'barang' for each detail
        $barangMasuk = BarangMasuk::with('detailBarangMasuk.barang')->find($id);
    
        if (!$barangMasuk) {
            return response()->json(['message' => 'Data not found'], 404);
        }
    
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    // Menampilkan form edit barang masuk
    public function edit($id)
    {
        $barangMasuk = BarangMasuk::with('detailBarangMasuk')->findOrFail($id);
        $depos = Depo::all();
        $barangs = Barang::all();
        return view('barang_masuk.edit', compact('barangMasuk','depos','barangs'));
    }

    // Memperbarui data barang masuk
    public function update(Request $request, $id_masuk)
    {
        // Validasi input
        $request->validate([
            'tanggal_masuk' => 'required|date',
            'id_depo' => 'required|exists:depos,id_depo',
            'keterangan' => 'nullable',
            'barang' => 'required|array',
            'barang.*' => 'required|exists:barang,id_barang',
            'jumlah_dos' => 'required|array',
            'jumlah_dos.*' => 'required|numeric',
            'jumlah_pcs' => 'required|array',
            'jumlah_pcs.*' => 'required|numeric',
        ]);
    
        // Ambil data barang masuk yang akan diupdate
        $barangMasuk = BarangMasuk::findOrFail($id_masuk);
    
        // Update data barang masuk
        $barangMasuk->update([
            'tanggal_masuk' => $request->tanggal_masuk,
            'id_depo' => $request->id_depo,
            'keterangan' => $request->keterangan,
        ]);
    
        // Hapus detail barang masuk lama dan kembalikan stok
        foreach ($barangMasuk->detailBarangMasuk as $detail) {
            $barang = Barang::find($detail->id_barang);
            if ($barang) {
                $barang->stok_dos -= $detail->jumlah_dos;
                $barang->stok_pcs -= $detail->jumlah_pcs;
                $barang->save();
            }
        }
        $barangMasuk->detailBarangMasuk()->delete();
    
        // Simpan data detail barang masuk baru dan update stok barang
        $detailBarangMasuk = [];
        for ($i = 0; $i < count($request->barang); $i++) {
            $detailBarangMasuk[] = new DetailBarangMasuk([
                'id_barang' => $request->barang[$i],
                'jumlah_dos' => $request->jumlah_dos[$i],
                'jumlah_pcs' => $request->jumlah_pcs[$i],
                'id_masuk' => $barangMasuk->id_masuk,
            ]);
    
            // Update stok barang di tabel barang
            $barang = Barang::find($request->barang[$i]);
            if ($barang) {
                $barang->stok_dos += $request->jumlah_dos[$i];
                $barang->stok_pcs += $request->jumlah_pcs[$i];
                $barang->save(); // Simpan perubahan stok
            }
        }
        // Simpan banyak detail barang masuk baru
        $barangMasuk->detailBarangMasuk()->saveMany($detailBarangMasuk);
    
        // Redirect atau kembalikan respons sesuai kebutuhan
        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil diperbarui dan stok diperbarui.');
    }

    // Menghapus barang masuk dan detailnya
    public function destroy($id_masuk)
    {
        $barangMasuk = BarangMasuk::findOrFail($id_masuk);
        $barangMasuk->detailBarangMasuk()->delete();
        $barangMasuk->delete();
    
        return redirect()->route('barangmasuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }
}
