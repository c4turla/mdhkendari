<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $table = 'barang';
    protected $primaryKey = 'id_barang';

    protected $fillable = [
        'nama_barang',
        'barcode',
        'keterangan',
        'satuan_per_dos',
        'stok_dos',
        'stok_pcs',
        'stok_lainnya',
        'satuan_lainnya'
    ];

    public function hargaBarang()
    {
        return $this->hasMany(HargaBarang::class, 'id_barang');
    }

    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_barang');
    }

    public function barangReturns()
    {
        return $this->hasMany(BarangReturn::class, 'id_barang');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_barang');
    }
}
