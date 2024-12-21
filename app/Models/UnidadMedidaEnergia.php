<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadMedidaEnergia extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_type_unit_med_energy'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'medene_cod'; // Llave primaria
    public $timestamps = false;

    //protected $keyType = 'string'; // Define que es un string

    protected $fillable = [
        'medene_cod',
        'consene_id',
        'medene_nombre',
    ];

    /**
     * Relación con la tabla de consumo de energías.
     */
    public function unidadMedida()
{
    return $this->belongsTo(UnidadMedidaEnergia::class, 'consene_id', 'consene_id');
}
}
