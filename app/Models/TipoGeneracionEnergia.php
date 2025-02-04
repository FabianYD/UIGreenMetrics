<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoGeneracionEnergia extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_TYPE_GEN_ENERGY';
    protected $primaryKey = 'GENTYPE_ID';
    public $incrementing = true;
    protected $keyType = 'integer';
    public $timestamps = false;

    protected $fillable = [
        'GENTYPE_ID',
        'GENTYPE_DETALLE',
        'GENTYPE_VALOR'
    ];
}
