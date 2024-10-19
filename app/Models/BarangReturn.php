<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangReturn extends Model
{
    use HasFactory;

    protected $table = 'barang_return';
    protected $primaryKey = 'id_return';
    public $incrementing = true; // Set this to false if your primary key is not auto-incrementing
    protected $keyType = 'int'; 

    protected $fillable = [
        'id_faktur',
        'id_barang',
        'tanggal_return',
        'jumlah_dos',
        'jumlah_pcs',
        'created_at',
        'updated_at'
    ];

    // Relationship with FakturPenjualan

    public function faktur()
    {
        return $this->belongsTo(FakturPenjualan::class, 'id_faktur');
    }

    // Relationship with Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    public function fakturDetail()
    {
        return $this->belongsTo(DetailFakturPenjualan::class, 'id_barang');
    }
}
