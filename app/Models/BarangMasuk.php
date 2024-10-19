<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $primaryKey = 'id_masuk'; 

    protected $fillable = [
        'kode_barang_masuk',
        'tanggal_masuk',
        'id_depo',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    // Relasi ke detail barang masuk
    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_masuk', 'id_masuk');
    }

    public function depo()
    {
        return $this->belongsTo(Depo::class, 'id_depo', 'id_depo');
    }


    public static function generateKode()
    {
        // Set timezone ke lokal, misal Asia/Jakarta
        date_default_timezone_set('Asia/Jakarta'); 
    
        // Menggunakan Carbon untuk tanggal hari ini dengan timezone yang diatur
        $today = Carbon::now()->format('dmY'); // Format tanggal hari ini: DDMMYY
        $prefix = 'BM';
        $suffix = '0001'; // Default suffix
    
        // Cek apakah ada entri hari ini
        $latest = self::whereDate('tanggal_masuk', Carbon::today())->orderBy('tanggal_masuk', 'desc')->first();
    
        if ($latest) {
            $latestKode = $latest->kode_barang_masuk;
            $latestSuffix = intval(substr($latestKode, -4)) + 1;
            $suffix = str_pad($latestSuffix, 4, '0', STR_PAD_LEFT);
        }
    
        return $prefix . $today . '-' . $suffix;
    }
}
