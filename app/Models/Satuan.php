<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;

    protected $table = 'satuan';

    protected $fillable = [
        'nama_satuan', 'is_default', 'id_satuan_induk', 'faktor_konversi'
    ];

    public function satuanInduk()
    {
        return $this->belongsTo(Satuan::class, 'id_satuan_induk');
    }

    public function satuanTurunan()
    {
        return $this->hasMany(Satuan::class, 'id_satuan_induk');
    }

    public function stok()
    {
        return $this->hasMany(Stok::class, 'id_satuan');
    }
}
