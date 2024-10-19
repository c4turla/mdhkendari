<?php

namespace App\Http\Controllers;

use App\Models\BarangReturn;
use App\Models\FakturPenjualan;
use App\Models\DetailFakturPenjualan;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class BarangReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barangReturns = BarangReturn::with('faktur', 'barang')->get();
        return view('barang_return.index', compact('barangReturns'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $fakturs = FakturPenjualan::all();
        $barangs = Barang::all();
        return view('barang_return.create', compact('fakturs', 'barangs'));
    }

    public function getFakturDetails($id_faktur)
    {
        // Mengambil faktur beserta detailnya
        $faktur = FakturPenjualan::with('detailFakturPenjualan.barang')->findOrFail($id_faktur);

        // Mengambil data outlet dan barang
        $barangs = Barang::all();

        // Membuat array untuk menyimpan detail barang
        $details = $faktur->detailFakturPenjualan->map(function ($detail) {
            return [
                'id_barang' => $detail->id_barang,
                'nama_barang' => $detail->barang->nama_barang,
                'jumlah_dos' => $detail->jumlah_dos,
                'jumlah_pcs' => $detail->jumlah_pcs
            ];
        });

        // Mengirimkan data sebagai JSON
        return response()->json([
            'details' => $details,
            'barangs' => $barangs
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_faktur' => 'required|exists:faktur_penjualan,id_faktur',
            'tanggal_return' => 'required|date',
            'barangs' => 'required|array',
            'barangs.*.id_barang' => 'required|exists:barang,id_barang',
            'barangs.*.jumlah_dos' => 'nullable|numeric',
            'barangs.*.jumlah_pcs' => 'nullable|numeric',
        ]);

        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Simpan data barang return
            foreach ($request->barangs as $barangData) {
                $barangReturn = new BarangReturn();
                $barangReturn->id_faktur = $request->id_faktur;
                $barangReturn->id_barang = $barangData['id_barang'];
                $barangReturn->tanggal_return = $request->tanggal_return;
                $barangReturn->jumlah_dos = $barangData['jumlah_dos'] ?? 0;
                $barangReturn->jumlah_pcs = $barangData['jumlah_pcs'] ?? 0;
                $barangReturn->save();

                // Update stok barang
                $barang = Barang::findOrFail($barangData['id_barang']);
                $barang->stok_dos += $barangData['jumlah_dos'] ?? 0;
                $barang->stok_pcs += $barangData['jumlah_pcs'] ?? 0;
                $barang->save();
            }

            // Commit transaksi
            DB::commit();

            return redirect()->route('barang_return.index')->with('success', 'Barang return berhasil disimpan.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat menyimpan data barang return.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BarangReturn $barangReturn)
    {
        return view('barang_return.show', compact('barangReturn'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BarangReturn $barangReturn)
    {
        $fakturs = FakturPenjualan::all();
        $barangs = Barang::all();
        return view('barang_return.edit', compact('barangReturn', 'fakturs', 'barangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BarangReturn $barangReturn)
    {
        $validatedData = $request->validate([
            'id_faktur' => 'required|exists:faktur_penjualan,id',
            'id_barang' => 'required|exists:barang,id',
            'jumlah_dos' => 'required|integer|min:0',
            'jumlah_pcs' => 'required|integer|min:0',
        ]);

        $barangReturn->update($validatedData);

        return redirect()->route('barang_return.index')->with('success', 'Barang return successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Mulai transaksi database
        DB::beginTransaction();

        try {
            // Ambil data barang return yang akan dihapus
            $barangReturn = BarangReturn::findOrFail($id);

            // Update stok barang sebelum menghapus
            $barang = Barang::findOrFail($barangReturn->id_barang);
            $barang->stok_dos -= $barangReturn->jumlah_dos;
            $barang->stok_pcs -= $barangReturn->jumlah_pcs;
            $barang->save();

            // Hapus data barang return
            $barangReturn->delete();

            // Commit transaksi
            DB::commit();

            return redirect()->route('barang_return.index')->with('success', 'Barang return berhasil dihapus.');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat menghapus data barang return.');
        }
    }
}
