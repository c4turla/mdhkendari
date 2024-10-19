<?php
namespace App\Http\Controllers;

use App\Models\SuratJalan;
use App\Models\SuratJalanDetail;
use App\Models\SuratJalanDetailHistory;
use App\Models\FakturPenjualan;
use App\Models\DetailFakturPenjualan;
use App\Models\Barang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class SuratJalanController extends Controller
{
    // Menampilkan daftar surat jalan dengan pencarian dan pagination
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        // Query untuk mencari berdasarkan no_surat_jalan dan id_faktur
        $query = SuratJalan::with('faktur', 'sales');

        if ($search) {
            $query->where('no_surat_jalan', 'like', "%{$search}%")
                  ->orWhereHas('sales', function($q) use ($search) {
                      $q->where('full_name', 'like', "%{$search}%");
                  });
        }

        // Paginate hasil
        $suratJalans = $query->orderBy('created_at', 'desc')->paginate(10); // Menampilkan 10 surat jalan per halaman

        return view('surat_jalan.index', compact('suratJalans'));
    }

    // Menampilkan form create surat jalan
    public function create()
    {
        // Mengambil semua faktur penjualan dan sales
        $fakturs = FakturPenjualan::with('outlet')->get();
        $sales = User::where('user_level', 'sales')->pluck('full_name', 'id');
        $kode = SuratJalan::generateKode();
        return view('surat_jalan.create', compact('fakturs', 'sales' , 'kode'));
    }

    // Menyimpan surat jalan baru
    public function store(Request $request)
    {
        $request->validate([
            'no_surat_jalan' => 'required|string|max:255',
            'fakturs' => 'required|array',
            'id_sales' => 'required|exists:users,id',
            'tanggal_surat' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
    
        // Membuat Surat Jalan
        $suratJalan = SuratJalan::create([
            'no_surat_jalan' => $request->no_surat_jalan,
            'id_sales' => $request->id_sales,
            'tanggal_surat' => $request->tanggal_surat,
            'keterangan' => $request->keterangan,
        ]);
    
        $lastInsertedId = $suratJalan->id;
        \Log::info('ID Surat Jalan yang baru dibuat: ' . $lastInsertedId);
        // Menyiapkan array untuk menyimpan detail surat jalan
        $details = [];
    
        foreach ($request->fakturs as $fakturId) {
            $faktur = FakturPenjualan::find($fakturId);
    
            // Memastikan faktur ditemukan
            if ($faktur) {
                foreach ($faktur->detailFakturPenjualan as $detail) {
                    $key = $detail->id_barang;
    
                    // Jika barang sudah ada dalam array, tambahkan jumlahnya
                    if (isset($details[$key])) {
                        $details[$key]['jumlah_dos'] += $detail->jumlah_dos;
                        $details[$key]['jumlah_pcs'] += $detail->jumlah_pcs;
                    } else {
                        // Jika barang belum ada, tambahkan ke array
                        $details[$key] = [
                            'id_barang' => $detail->id_barang,
                            'jumlah_dos' => $detail->jumlah_dos,
                            'jumlah_pcs' => $detail->jumlah_pcs,
                            'fakturs' => [$fakturId] // Simpan ID faktur untuk referensi
                        ];
                    }
                }
            }
        }
    
        // Simpan detail ke tabel surat jalan
        foreach ($details as $detail) {
            $totalJumlah = ($detail['jumlah_dos'] ) + ($detail['jumlah_pcs']); // Hitung total jumlah berdasarkan harga
    
            // Loop melalui ID faktur terkait untuk menyimpan detail
            foreach ($detail['fakturs'] as $fakturId) {
                $suratJalan->details()->create([
                    'id_faktur' => $fakturId,
                    'id_barang' => $detail['id_barang'],
                    'jumlah_dos' => $detail['jumlah_dos'],
                    'jumlah_pcs' => $detail['jumlah_pcs'],
                    'total_jumlah' => $totalJumlah,
                ]);
            }
        }
        // Panggil stored procedure setelah data berhasil disimpan
        $this->updateStokBarang($suratJalan->id_surat_jalan);
           
        return redirect()->route('surat_jalan.index')->with('success', 'Surat Jalan berhasil dibuat.');
    }
    
    private function updateStokBarang($idSuratJalan)
    {
        DB::statement('CALL update_stock_after_delivery(?)', [$idSuratJalan]);
    }

    // Menampilkan detail surat jalan
    public function show($id)
    {
        $suratJalan = SuratJalan::with(['sales', 'details.barang'])->findOrFail($id);
        return view('surat_jalan.show', compact('suratJalan'));
    }

    // Menampilkan form edit surat jalan
    public function edit($id)
    {
        $suratJalan = SuratJalan::findOrFail($id);
        // Mengambil semua faktur penjualan
        $fakturs = FakturPenjualan::all();
        $sales = User::where('user_level', 'sales')->pluck('full_name', 'id');
        return view('surat_jalan.edit', compact('suratJalan', 'fakturs','sales'));
    }

    // Memperbarui surat jalan
    public function update(Request $request, $id)
    {
        $request->validate([
            'no_surat_jalan' => 'required|string|max:255',
            'fakturs' => 'required|array',
            'id_sales' => 'required|exists:users,id',
            'tanggal_surat' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);
    
        DB::beginTransaction();
    
        try {
            $suratJalan = SuratJalan::findOrFail($id);
    
            // Simpan data lama ke dalam history sebelum update
            $this->saveToHistory($suratJalan);
    
            // Update Surat Jalan
            $suratJalan->update([
                'no_surat_jalan' => $request->no_surat_jalan,
                'id_sales' => $request->id_sales,
                'tanggal_surat' => $request->tanggal_surat,
                'keterangan' => $request->keterangan,
            ]);
    
            // Hapus detail surat jalan yang lama
            $suratJalan->details()->delete();
    
            // Menyiapan dan menyimpan detail surat jalan yang baru
            foreach ($request->fakturs as $fakturId) {
                $faktur = FakturPenjualan::find($fakturId);
    
                if ($faktur) {
                    foreach ($faktur->detailFakturPenjualan as $detail) {
                        $suratJalan->details()->create([
                            'id_faktur' => $fakturId,
                            'id_barang' => $detail->id_barang,
                            'jumlah_dos' => $detail->jumlah_dos,
                            'jumlah_pcs' => $detail->jumlah_pcs,
                            'total_jumlah' => ($detail->jumlah_dos) + ($detail->jumlah_pcs),
                        ]);
                    }
                }
            }
    
            // Panggil stored procedure untuk update stok barang
            $this->updateStokAfterSuratJalanUpdate($suratJalan->id_surat_jalan);
    
            DB::commit();
    
            return redirect()->route('surat_jalan.index')->with('success', 'Surat Jalan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    private function saveToHistory($suratJalan)
    {
        foreach ($suratJalan->details as $detail) {
            DB::table('surat_jalan_detail_history')->insert([
                'id_surat_jalan' => $suratJalan->id_surat_jalan,
                'id_barang' => $detail->id_barang,
                'jumlah_dos' => $detail->jumlah_dos,
                'jumlah_pcs' => $detail->jumlah_pcs,
                // Tambahkan kolom lain yang diperlukan
            ]);
        }
    }

    private function updateStokBarangAfterEdit($idSuratJalan)
    {
        DB::statement('CALL update_stock_after_surat_jalan_update(?)', [$idSuratJalan]);
    }

    // Fungsi cetak PDF
    public function cetakSuratJalan($id)
    {
        // Ambil data surat jalan dengan relasi yang diperlukan
        $suratJalan = SuratJalan::with(['sales', 'details.barang'])->findOrFail($id);
       // $faktur = FakturPenjualan::with('outlet.zona', 'outlet.sales', 'detailFakturPenjualan.barang')->findOrFail($id);
       
        // Load view dan generate PDF
        $pdf = PDF::loadView('pdf.suratjalan', compact('suratJalan'));
       
        // Return PDF ke browser untuk didownload atau ditampilkan
        return $pdf->stream('surat-jalan.pdf');
    }
    

    // Menghapus surat jalan
    public function destroy($id)
    {
        DB::beginTransaction();
    
        try {
            $suratJalan = SuratJalan::findOrFail($id);
    
            // Panggil stored procedure untuk mengembalikan stok
            $this->restoreStockAfterSuratJalanDelete($suratJalan->id_surat_jalan);
    
            // Hapus detail surat jalan
            $suratJalan->details()->delete();
    
            // Hapus surat jalan
            $suratJalan->delete();
    
            DB::commit();
    
            return redirect()->route('surat_jalan.index')->with('success', 'Surat Jalan berhasil dihapus dan stok barang telah dikembalikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat menghapus Surat Jalan: ' . $e->getMessage());
        }
    }

    private function restoreStockAfterSuratJalanDelete($idSuratJalan)
    {
        DB::statement('CALL restore_stock_after_surat_jalan_delete(?)', [$idSuratJalan]);
    }

}
