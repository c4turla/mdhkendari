<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFakturPenjualan extends Model
{
    use HasFactory;

    protected $table = 'faktur_penjualan_detail';
    protected $primaryKey = 'id_detail_faktur';

    protected $fillable = [
        'id_faktur',
        'id_barang',
        'jumlah_dos',
        'jumlah_pcs',
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

    public function getJumlahFormattedAttribute()
    {
        if ($this->jumlah_dos > 0) {
            return $this->jumlah_dos . ' Dos';
        } elseif ($this->jumlah_pcs > 0) {
            return $this->jumlah_pcs . ' Pcs';
        } else {
            return '0';
        }
    }
}
