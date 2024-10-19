<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Models\Outlet;
use App\Models\FakturPenjualan;
use App\Models\DetailFakturPenjualan;
use App\Models\SuratJalanDetail;
use App\Models\SuratJalan;
use App\Models\Barang;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        // Mengecek apakah view dengan path yang diminta ada
        if (view()->exists($request->path())) {
            // Ambil data outlet
            $outletData = $this->getOutletData();
    
            // Ambil faktur penjualan terakhir dengan relasi outlet
            $fakturPenjualanTerbaru = FakturPenjualan::with('outlet')->orderBy('created_at', 'desc')->take(5)->get();
    
            // Menghitung total pendapatan dan persentase perubahan
            list($totalRevenue, $percentageChange) = $this->calculateRevenue();

            $topProducts = $this->getTop5Products();

            $topSales = $this->getTop3Sales();
    
    
            // Gabungkan semua data
            $data = array_merge($outletData, [
                'fakturPenjualanTerbaru' => $fakturPenjualanTerbaru,
                'totalRevenue' => $totalRevenue,
                'percentageChange' => $percentageChange,
                'topProducts' => $topProducts,
                'topSales' => $topSales
            ]);
    
            return view($request->path(), $data);
        }
    
        // Jika tidak ada view yang sesuai, mengembalikan 404
        return abort(404);
    }
    
    public function root()
    {
        // Ambil data outlet
        $outletData = $this->getOutletData();
    
        // Ambil faktur penjualan terbaru
        $fakturPenjualanTerbaru = $this->getRecentFakturPenjualan();
    
        // Menghitung total pendapatan dan persentase perubahan
        list($totalRevenue, $percentageChange) = $this->calculateRevenue();

        // Ambil data top 5 barang paling laku
        $topProducts = $this->getTop5Products();

        $topSales = $this->getTop3Sales();

    
        // Gabungkan semua data
        $data = array_merge($outletData, [
            'fakturPenjualanTerbaru' => $fakturPenjualanTerbaru,
            'totalRevenue' => $totalRevenue,
            'percentageChange' => $percentageChange,
            'topProducts' => $topProducts,
            'topSales' => $topSales
        ]);
    
        return view('index', $data);
    }

    public function showDetail($id)
    {
        // Mengambil data faktur beserta relasi-relasinya
        $faktur = FakturPenjualan::with('outlet.zona', 'outlet.sales', 'detailFakturPenjualan.barang')->findOrFail($id);
    
        // Buat HTML yang akan dikirimkan melalui Ajax
        $html = '
        <div>
            <dl class="row mb-0">
                <dt class="col-sm-3">Nomor Faktur</dt>
                <dd class="col-sm-9"><strong>: ' . $faktur->nomor_bukti . '</strong></dd>
                <dt class="col-sm-3">Nama Toko</dt>
                <dd class="col-sm-9"><strong>: ' . $faktur->outlet->nama . '</strong></dd>
                <dt class="col-sm-3">Alamat</dt>
                <dd class="col-sm-9">: ' . $faktur->outlet->alamat . '</dd>
                <dt class="col-sm-3">Sales</dt>
                <dd class="col-sm-9"><strong>: ' . $faktur->outlet->sales->full_name . '</strong></dd>
                <dt class="col-sm-3">Area</dt>
                <dd class="col-sm-9"><strong>: ' . $faktur->outlet->zona->nama . '</strong></dd>
                <dt class="col-sm-3">Tanggal Buat</dt>
                <dd class="col-sm-9"><strong>: ' . $faktur->tanggal_buat . '</strong></dd>
                <dt class="col-sm-3">Tanggal Jatuh Tempo</dt>
                <dd class="col-sm-9"><strong>: ' . ($faktur->tanggal_jatuh_tempo ?? '-') . '</strong></dd>
                <dt class="col-sm-3">Cara Bayar</dt>
                <dd class="col-sm-9"><strong>: ' . $faktur->cara_pembayaran . '</strong></dd>
            </dl>    
            <hr>    
            <table class="table table-bordered table-striped">
                <thead class="thead-light">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>';
                    
        foreach ($faktur->detailFakturPenjualan as $detail) {
            $html .= '
                    <tr>
                        <td>' . $detail->barang->nama_barang . '</td>
                        <td>' . $detail->jumlah_formatted . '</td>
                        <td class="text-end">Rp ' . number_format($detail->harga, 0, ',', '.') . '</td>
                        <td class="text-end">Rp ' . number_format($detail->diskon, 0, ',', '.') . '</td>
                        <td class="text-end">Rp ' . number_format($detail->total_harga, 0, ',', '.') . '</td>
                    </tr>';
        }
    
        $html .= '
                    <tr>
                        <td colspan="4" class="text-end"><strong>Grand Total</strong></td>
                        <td class="text-end">Rp ' . number_format($faktur->grand_total, 0, ',', '.') . '</td>
                    </tr>
                </tbody>        
            </table>
        </div>';
    
        // Kirimkan respons dalam bentuk JSON
        return response()->json(['html' => $html]);
    }
    
    private function getOutletData()
    {
        $currentTotal = Outlet::count();
        $lastWeekTotal = Outlet::where('created_at', '<', Carbon::now()->subWeek())->count();

        $percentageChange = 0;
        if ($lastWeekTotal > 0) {
            $percentageChange = (($currentTotal - $lastWeekTotal) / $lastWeekTotal) * 100;
        }

        return [
            'totalOutlets' => $currentTotal,
            'percentageChange' => round($percentageChange, 2),
            'isIncrease' => $percentageChange >= 0
        ];
    }

    // Fungsi terpisah untuk mengambil top 5 barang paling laku
    private function getTop5Products()
    {
    return SuratJalanDetail::select('id_barang', \DB::raw('SUM(total_jumlah) as total_terjual'))
                        ->groupBy('id_barang')
                        ->orderBy('total_terjual', 'desc')
                        ->take(5)
                        ->with('barang')
                        ->get();
    }

    public function getTop3Sales()
    {
        // Ambil data top 3 sales berdasarkan jumlah surat jalan
        return SuratJalan::select('id_sales', \DB::raw('COUNT(id_sales) as total_transaksi'))
        ->groupBy('id_sales')
        ->orderBy('total_transaksi', 'desc')
        ->take(3)
        ->with('sales') // Relasi dengan tabel user untuk mendapatkan nama sales
        ->get();

    }

    private function calculateRevenue()
    {
        // Total revenue minggu ini
        $thisWeekRevenue = FakturPenjualan::whereBetween('created_at', [
            Carbon::now()->startOfWeek(), Carbon::now()->endOfDay()
        ])->sum('grand_total');

        // Total revenue minggu lalu
        $lastWeekRevenue = FakturPenjualan::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()
        ])->sum('grand_total');

        // Menghitung persentase perubahan
        $percentageChange = $lastWeekRevenue > 0 
            ? (($thisWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100 
            : 0;

        return [$thisWeekRevenue, $percentageChange];
    }


    private function getRecentFakturPenjualan()
    {
        return FakturPenjualan::with('outlet')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    }

    /*Language Translation*/
    public function lang($locale)
    {
        if ($locale) {
            App::setLocale($locale);
            Session::put('lang', $locale);
            Session::save();
            return redirect()->back()->with('locale', $locale);
        } else {
            return redirect()->back();
        }
    }

    public function FormSubmit(Request $request)
    {
        return view('form-repeater');
    }
}