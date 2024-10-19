<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SuratJalan extends Model
{
    // Nama tabel
    protected $table = 'surat_jalan';

    // Primary key
    protected $primaryKey = 'id_surat_jalan';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'no_surat_jalan', 'id_sales', 'tanggal_surat', 'keterangan'
    ];

    // Relasi ke model FakturPenjualan
    public function faktur()
    {
        return $this->belongsTo(FakturPenjualan::class, 'id_faktur');
    }

    // Relasi ke model Sales
    public function sales()
    {
        return $this->belongsTo(User::class, 'id_sales');
    }

    // Relasi ke model SuratJalanDetail
    public function details()
    {
        return $this->hasMany(SuratJalanDetail::class, 'id_surat_jalan');
    }

    public static function generateKode()
    {
        // Set timezone ke lokal, misal Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta'); 
    
        // Menggunakan Carbon untuk tanggal hari ini dengan timezone yang diatur
        $today = Carbon::now()->format('dmY'); // Format tanggal hari ini: DDMMYY
        $prefix = 'SJ';
        $suffix = '0001'; // Default suffix
    
        // Cek apakah ada entri hari ini
        $latest = self::whereDate('created_at', Carbon::today())->orderBy('created_at', 'desc')->first();
    
        if ($latest) {
            $latestKode = $latest->no_surat_jalan;
            $latestSuffix = intval(substr($latestKode, -4)) + 1;
            $suffix = str_pad($latestSuffix, 4, '0', STR_PAD_LEFT);
        }
    
        return $prefix . $today . '-' . $suffix;
    }
}
