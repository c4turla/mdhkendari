<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class FakturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'faktur_penjualan';
    protected $primaryKey = 'id_faktur';

    protected $fillable = [
        'id_outlet',
        'tanggal_buat',
        'tanggal_jatuh_tempo',
        'cara_pembayaran',
        'nomor_bukti',
        'grand_total'
    ];

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }

    public function detailBaruFakturPenjualan()
    {
        return $this->hasMany(DetailBaruFakturPenjualan::class, 'id_faktur');
    }

    public function barangReturns()
    {
        return $this->hasMany(BarangReturn::class, 'id_faktur');
    }

    public static function generateKode()
    {
        // Set timezone ke lokal, misal Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta'); 
    
        // Menggunakan Carbon untuk tanggal hari ini dengan timezone yang diatur
        $today = Carbon::now()->format('dmY'); // Format tanggal hari ini: DDMMYY
        $prefix = 'FP';
        $suffix = '0001'; // Default suffix
    
        // Cek apakah ada entri hari ini
        $latest = self::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->first();
    
        if ($latest) {
            $latestKode = $latest->nomor_bukti;
            $latestSuffix = intval(substr($latestKode, -4)) + 1;
            $suffix = str_pad($latestSuffix, 4, '0', STR_PAD_LEFT);
        }
    
        return $prefix . $today . '-' . $suffix;
    }
}
