<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TratamientoAgua extends Model
{
    protected $table = 'gm_wec_tratamientos_aguas';
    protected $primaryKey = 'tragua_id';
    public $timestamps = false;

    protected $fillable = [
        'consag_id',
        'tipotra_cod',
        'tragua_total',
        'cantidad_sostenible',
        'eficiencia',
    ];

    // Relación con tipos de tratamiento
     public function tipoTratamiento()
    {
        return $this->belongsTo(TipoTratamiento::class, 'tipotra_cod', 'tipotra_cod');
    }

    // Relación con consumo de agua
    public function consumoAgua()
    {
        return $this->belongsTo(ConsumoAgua::class, 'consag_id', 'consag_id');
    }

    public function unidadMedida()
    {
        return $this->hasOneThrough(
            TypeUnidadMedAgua::class, // Modelo final
            ConsumoAgua::class,       // Modelo intermedio
            'consag_id',              // Foreign key en ConsumoAgua
            'consag_id',              // Foreign key en TypeUnidadMedAgua
            'consag_id',              // Local key en TratamientoAgua
            'consag_id'               // Local key en ConsumoAgua
        );
    }

}
