<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk_detail';
    protected $primaryKey = 'id_detail_masuk'; 

    protected $fillable = [
        'id_masuk',
        'id_barang',
        'jumlah_dos',
        'jumlah_pcs',
        'jumlah_lainnya',
        'satuan_lainnya',
        'created_at',
        'updated_at'
    ];

    // Relasi ke barang masuk
    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_masuk', 'id_masuk');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
