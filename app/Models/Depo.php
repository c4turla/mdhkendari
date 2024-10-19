<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depo extends Model
{
    use HasFactory;

    protected $table = 'depos';

    protected $primaryKey = 'id_depo';

    protected $fillable = ['nama_depo'];
}
