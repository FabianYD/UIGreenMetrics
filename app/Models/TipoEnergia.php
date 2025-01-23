<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoEnergia extends Model
{
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

    public function consumos(): HasMany
    {
        return $this->hasMany(ConsumoEnergia::class, 'TIPOENE_ID', 'TIPOENE_ID');
    }
}
