<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UnidadMedidaAgua extends Model
{
    protected $table = 'GM_WEC_TYPE_UNIDAD_MED_AGUA';
    protected $primaryKey = 'MEDIDADAG_COD';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MEDIDADAG_COD',
        'MEDIDAAGU_NOMBRE'
    ];

    public function consumos(): HasMany
    {
        return $this->hasMany(ConsumoAgua::class, 'MEDIDADAG_COD', 'MEDIDADAG_COD');
    }
}
