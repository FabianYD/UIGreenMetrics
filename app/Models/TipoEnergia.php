<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoEnergia extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_TIPOS_ENERGIAS';
    protected $primaryKey = 'TIPOENE_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'TIPOENE_ID',
        'TIPOENE_NOMBRES',
        'TIPOENE_DETALLE'
    ];
}
