<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneracionEnergia extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'gm_wec_generacion_energias';
    
    // Clave primaria
    protected $primaryKey = 'genene_id';
    
    // La clave primaria es autoincremental
    public $incrementing = true;

    // Indicar que no se manejarán timestamps
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'facu_codigo', // Código de la facultad asociada
        'genene_total',  // Total de generación de energía
    ];

    /**
     * Relación con la facultad.
     * Una generación de energía pertenece a una facultad.
     */
    public function facultad()
    {
        return $this->belongsTo(Facultad::class, 'facu_codigo', 'facu_codigo');
    }
}
