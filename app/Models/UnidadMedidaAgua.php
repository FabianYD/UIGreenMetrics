<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ConsumoAgua;

class UnidadMedidaAgua extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_TYPE_UNIDAD_MED_AGUA';
    protected $primaryKey = 'MEDIDADAG_COD';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MEDIDADAG_COD',
        'MEDIDAAGU_NOMBRE'
    ];

    // Relaciones
    public function consumosAgua()
    {
        return $this->hasMany(ConsumoAgua::class, 'MEDIDADAG_COD', 'MEDIDADAG_COD');
    }
}
