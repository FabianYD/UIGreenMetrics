<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoTratamiento extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_TIPOS_TRATAMIENTOS';
    protected $primaryKey = 'TIPOTRA_COD';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'TIPOTRA_COD',
        'TIPOTRA_NOMBRES',
        'TIPOTRA_DETALLE'
    ];
}
