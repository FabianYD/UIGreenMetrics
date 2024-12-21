<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facultad extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'gm_wec_facultades';
    
    // Clave primaria
    protected $primaryKey = 'facu_codigo';
    
    // La clave primaria no es autoincremental
    public $incrementing = false;

    // Definir el tipo de la clave primaria
    protected $keyType = 'string';

    // Indicar que no se manejarán timestamps
    public $timestamps = false;

    // Campos que pueden ser asignados masivamente
    protected $fillable = [
        'facu_codigo',
        'campus_id',
        'facu_nombre',
    ];

    /**
     * Relación con el campus.
     * Un facultad pertenece a un campus.
     */
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'campus_id');
    }

    public function generacionEnergias()
    {
    return $this->hasMany(GeneracionEnergia::class, 'facu_codigo', 'facul_codigo');
    }
}
