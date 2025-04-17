<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailBaruFakturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'faktur_penjualan_detail_baru';
    protected $primaryKey = 'id_detail_faktur';

    protected $fillable = [
        'id_faktur',
        'id_barang',
        'satuan',
        'jumlah',
        'harga',
        'diskon',
        'total_harga'
    ];

    public function faktur()
    {
        return $this->belongsTo(FakturPenjualan::class, 'id_faktur');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }


}
