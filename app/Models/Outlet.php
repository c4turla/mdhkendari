<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlet extends Model
{
    use HasFactory;

    protected $table = 'outlets';

    protected $primaryKey = 'id_outlet';

    protected $fillable = [
        'nama',
        'nama_pemilik',
        'NIK',
        'phone',
        'id_sales',
        'id_zona',
        'alamat',
        'ktp',
        'latitude',
        'longitude',
    ];

    // Define relationships if needed
    public function sales()
    {
        return $this->belongsTo(User::class, 'id_sales');
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'id_zona');
    }
    
    public function fakturPenjualan()
    {
        return $this->hasMany(FakturPenjualan::class, 'id_outlet');
    }
}
