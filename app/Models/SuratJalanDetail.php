<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratJalanDetail extends Model
{
    // Nama tabel
    protected $table = 'surat_jalan_detail';

    // Primary key
    protected $primaryKey = 'id_surat_jalan_detail';

    // Kolom yang dapat diisi (fillable)
    protected $fillable = [
        'id_surat_jalan', 'id_faktur', 'id_barang', 'jumlah_dos', 'jumlah_pcs', 'total_jumlah'
    ];

    // Relasi ke model SuratJalan
    public function suratJalan()
    {
        return $this->belongsTo(SuratJalan::class, 'id_surat_jalan');
    }

    // Relasi ke model Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // Relasi ke model FakturPenjualan
    public function faktur()
    {
        return $this->belongsTo(FakturPenjualan::class, 'id_faktur');
    }

}
