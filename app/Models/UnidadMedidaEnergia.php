<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ConsumoEnergia;

class UnidadMedidaEnergia extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_TYPE_UNIDAD_MED_ENERGIAS';
    protected $primaryKey = 'MEDENE_COD';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MEDENE_COD',
        'MEDENE_NOMBRE'
    ];

    // Relaciones
    public function consumosEnergia()
    {
        return $this->hasMany(ConsumoEnergia::class, 'MEDENE_COD', 'MEDENE_COD');
    }
}
