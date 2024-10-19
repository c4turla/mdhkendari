<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';

    protected $primaryKey = 'id_zona';

    protected $fillable = ['nama', 'keterangan','id_depo'];

    public function depo()
    {
        return $this->belongsTo(Depo::class, 'id_depo');
    }
}
