<?php

namespace App\Http\Controllers;

use App\Models\FakturPenjualan;
use App\Models\DetailFakturPenjualan;
use App\Models\Outlet;
use App\Models\Barang;
use App\Models\HargaBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;
use PDF;


class FakturPenjualanController extends Controller
{

    // Menampilkan daftar faktur penjualan
    public function index(Request $request)
    {
        // Ambil input pencarian dari request
        $search = $request->input('search');
    
        // Query dasar FakturPenjualan
        $query = FakturPenjualan::query();
    
        // Jika ada input pencarian, tambahkan filter
        if ($search) {
            $query->where('nomor_bukti', 'like', '%' . $search . '%')
                  ->orWhereHas('outlet', function ($q) use ($search) {
                      $q->where('nama', 'like', '%' . $search . '%');
                  })
                  ->orWhere('cara_pembayaran', 'like', '%' . $search . '%');
        }
    
        // Sorting berdasarkan created_at desc
        $faktur_penjualan = $query->orderBy('created_at', 'desc')->paginate(10);
    
        return view('faktur_penjualan.index', compact('faktur_penjualan'));
    }

    // Menampilkan form pembuatan faktur baru
    public function create()
    {
        $outlets = Outlet::all();
        $barangs = Barang::all();
        $harga_barang = HargaBarang::all();
        $kode = FakturPenjualan::generateKode();
        return view('faktur_penjualan.create', compact('outlets', 'kode','barangs', 'harga_barang'));
    }

    // Mendapatkan harga barang berdasarkan id_barang dan zona_id
    public function getHargaBarang(Request $request)
    {
        $hargaBarang = HargaBarang::where('barang_id', $request->barang_id)
            ->where('zona_id', $request->zona_id)
            ->first();
    
        if (!$hargaBarang) {
            return response()->json([
                'error' => 'Harga Barang belum ada di Harga Per Zona.'
            ], 404); // Mengembalikan status 404 jika tidak ditemukan
        }
    
        return response()->json([
            'harga_per_dos' => $hargaBarang->harga_per_dos,
            'harga_per_pcs' => $hargaBarang->harga_per_pcs
        ]);
    }
    

    // Menyimpan faktur baru
    public function store(Request $request)
    {
        try {
            // Log the incoming request data
            \Log::info('Incoming Faktur Penjualan data:', $request->all());

            // Validasi input
            $validated = $request->validate([
                'nomor_bukti' => 'required|unique:faktur_penjualan,nomor_bukti',
                'id_outlet' => 'required|exists:outlets,id_outlet',
                'tanggal_buat' => 'required|date',
                'tanggal_jatuh_tempo' => 'nullable|date',
                'cara_pembayaran' => 'required',
                'grand_total' => 'required|numeric',
                'barang' => 'required|array',
                'barang.*' => 'required|exists:barang,id_barang',
                'jumlah_dos' => 'array',
                'jumlah_dos.*' => 'numeric|min:0',
                'jumlah_pcs' => 'array',
                'jumlah_pcs.*' => 'numeric|min:0',
                'harga' => 'required|array',
                'harga.*' => 'required|numeric|min:0',
                'diskon' => 'required|array',
                'diskon.*' => 'required|numeric|min:0',
                'total' => 'required|array',
                'total.*' => 'required|numeric|min:0',
            ]);

            \Log::info('Validated Faktur Penjualan data:', $validated);

            DB::beginTransaction();

            // Simpan Faktur
            $faktur = FakturPenjualan::create([
                'id_outlet' => $validated['id_outlet'],
                'tanggal_buat' => $validated['tanggal_buat'],
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                'cara_pembayaran' => $validated['cara_pembayaran'],
                'nomor_bukti' => $validated['nomor_bukti'],
                'grand_total' => $validated['grand_total'],
            ]);

            \Log::info('Created Faktur Penjualan:', $faktur->toArray());

            // Simpan detail barang
            $detailFakturPenjualan = [];
            foreach ($validated['barang'] as $index => $id_barang) {
                $detailFakturPenjualan[] = new DetailFakturPenjualan([
                    'id_barang' => $validated['barang'][$index],
                    'jumlah_dos' => $validated['jumlah_dos'][$index] ?? 0,
                    'jumlah_pcs' => $validated['jumlah_pcs'][$index] ?? 0,
                    'harga' => $validated['harga'][$index],
                    'diskon' => $validated['diskon'][$index],
                    'total_harga' => $validated['total'][$index],
                ]);
            }

            // Save detail records
            $faktur->detailFakturPenjualan()->saveMany($detailFakturPenjualan);

            \Log::info('Created DetailFakturPenjualan records:', $detailFakturPenjualan);

            DB::commit();

            \Log::info('Faktur Penjualan creation completed successfully');

            return redirect()->route('faktur_penjualan.index')->with('success', 'Faktur Penjualan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Faktur Penjualan creation failed: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Menampilkan detail faktur penjualan
    public function show($id)
    {
        try {
            // Mengambil data faktur beserta relasi dengan outlet (termasuk sales) dan detailFakturPenjualan serta barang
            $faktur = FakturPenjualan::with(['outlet.sales', 'detailFakturPenjualan.barang'])->findOrFail($id);
            
            // Menampilkan view jika data ditemukan
            return view('faktur_penjualan.show', compact('faktur'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Menangani jika data tidak ditemukan
            return response()->json(['message' => 'Faktur Penjualan not found'], 404);
        } catch (\Exception $e) {
            // Menangani kesalahan lain
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $faktur = FakturPenjualan::with('detailFakturPenjualan')->findOrFail($id);
        $outlets = Outlet::all();
        $barangs = Barang::all();

        return view('faktur_penjualan.edit', compact('faktur', 'outlets', 'barangs'));
    }

      // Fungsi untuk memperbarui data
      public function update(Request $request, $id)
      {
          try {
              // Log the incoming request data
              \Log::info('Updating Faktur Penjualan data:', $request->all());
  
              // Validasi input
              $validated = $request->validate([
                  'nomor_bukti' => 'required|unique:faktur_penjualan,nomor_bukti,' . $id . ',id_faktur',
                  'id_outlet' => 'required|exists:outlets,id_outlet',
                  'tanggal_buat' => 'required|date',
                  'tanggal_jatuh_tempo' => 'nullable|date',
                  'cara_pembayaran' => 'required',
                  'grand_total' => 'required|numeric',
                  'barang' => 'required|array',
                  'barang.*' => 'required|exists:barang,id_barang',
                  'jumlah_dos' => 'array',
                  'jumlah_dos.*' => 'numeric|min:0',
                  'jumlah_pcs' => 'array',
                  'jumlah_pcs.*' => 'numeric|min:0',
                  'harga' => 'required|array',
                  'harga.*' => 'required|numeric|min:0',
                  'diskon' => 'required|array',
                  'diskon.*' => 'required|numeric|min:0',
                  'total' => 'required|array',
                  'total.*' => 'required|numeric|min:0',
              ]);
  
              \Log::info('Validated Faktur Penjualan data:', $validated);
  
              DB::beginTransaction();
  
              // Update Faktur
              $faktur = FakturPenjualan::findOrFail($id);
              $faktur->update([
                  'id_outlet' => $validated['id_outlet'],
                  'tanggal_buat' => $validated['tanggal_buat'],
                  'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                  'cara_pembayaran' => $validated['cara_pembayaran'],
                  'nomor_bukti' => $validated['nomor_bukti'],
                  'grand_total' => $validated['grand_total'],
              ]);
  
              \Log::info('Updated Faktur Penjualan:', $faktur->toArray());
  
              // Hapus detail barang lama
              $faktur->detailFakturPenjualan()->delete();
  
              // Simpan detail barang baru
              $detailFakturPenjualan = [];
              foreach ($validated['barang'] as $index => $id_barang) {
                  $detailFakturPenjualan[] = new DetailFakturPenjualan([
                      'id_barang' => $validated['barang'][$index],
                      'jumlah_dos' => $validated['jumlah_dos'][$index] ?? 0,
                      'jumlah_pcs' => $validated['jumlah_pcs'][$index] ?? 0,
                      'harga' => $validated['harga'][$index],
                      'diskon' => $validated['diskon'][$index],
                      'total_harga' => $validated['total'][$index],
                  ]);
              }
  
              // Simpan detail yang baru
              $faktur->detailFakturPenjualan()->saveMany($detailFakturPenjualan);
  
              \Log::info('Updated DetailFakturPenjualan records:', $detailFakturPenjualan);
  
              DB::commit();
  
              \Log::info('Faktur Penjualan update completed successfully');
  
              return redirect()->route('faktur_penjualan.index')->with('success', 'Faktur Penjualan berhasil diperbarui.');
          } catch (\Exception $e) {
              DB::rollBack();
              \Log::error('Faktur Penjualan update failed: ' . $e->getMessage());
              \Log::error($e->getTraceAsString());
              return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
          }
      }

       // Fungsi cetak PDF
    public function cetakFaktur($id)
    {
        // Ambil data faktur dengan relasi yang diperlukan
        $faktur = FakturPenjualan::with('outlet.zona', 'outlet.sales', 'detailFakturPenjualan.barang')->findOrFail($id);

        // Load view dan generate PDF
        $pdf = PDF::loadView('pdf.faktur', compact('faktur'));

        // Return PDF ke browser untuk didownload atau ditampilkan
        return $pdf->stream('faktur-penjualan.pdf');
    }
    
    // Menghapus barang masuk dan detailnya
    public function destroy($id_faktur)
    {
        $fakturPenjualan = FakturPenjualan::findOrFail($id_faktur);
        $fakturPenjualan->detailFakturPenjualan()->delete();
        $fakturPenjualan->delete();
        
        return redirect()->route('faktur_penjualan.index')->with('success', 'Data Faktur Penjualan berhasil dihapus.');
    }
}
