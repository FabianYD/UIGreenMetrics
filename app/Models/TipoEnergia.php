<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoEnergia extends Model
{
    use HasFactory;

    // Nombre de la tabla asociada
    protected $table = 'gm_wec_tipos_energias';

    // Clave primaria de la tabla
    protected $primaryKey = 'tipoene_id';

    // La tabla no tiene timestamps
    public $timestamps = false;
    // Definir los tipos de datos
    protected $keyType = 'string';

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'tipoene_id',
        'tipoene_nombres',
        'tipoene_detalle',
    ];

    /**
     * Relación con el modelo ConsumoEnergia.
     */
    public function consumosEnergia()
    {
        return $this->hasMany(ConsumoEnergia::class, 'tipoene_id', 'tipoene_id');
    }
}
