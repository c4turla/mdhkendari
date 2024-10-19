<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaBarang extends Model
{
    use HasFactory;

    protected $table = 'harga_barang';
    protected $primaryKey = 'id_harga';

    protected $fillable = [
        'barang_id',
        'zona_id',
        'harga_per_dos',
        'harga_per_pcs',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id', 'id_zona');
    }
}
