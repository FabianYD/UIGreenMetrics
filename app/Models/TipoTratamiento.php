<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTratamiento extends Model
{
    protected $table = 'gm_wec_tipos_tratamientos';
    protected $primaryKey = 'tipotra_cod';
    public $incrementing = false; // Clave primaria no es autoincremental
    protected $keyType = 'string'; // Define que es un string
    protected $fillable = [
        'tipotra_nombres',
        'tipotra_detalle',
        'porcentaje_purificacion',
    ];

    // Relación con tratamientos de agua
    public function tratamientosAgua()
    {
        return $this->hasMany(TratamientoAgua::class, 'tipotra_cod', 'tipotra_cod');
    }
}
