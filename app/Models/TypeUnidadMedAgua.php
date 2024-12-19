<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeUnidadMedAgua extends Model
{
    protected $table = 'gm_wec_type_unidad_med_agua'; // Nombre correcto de la tabla
    protected $primaryKey = 'consag_id'; // Llave primaria de la tabla
    public $timestamps = false;

    protected $fillable = [
        'consag_id', // Código de unidad de medida
        'medidaagu_nombre', // Nombre de la unidad de medida
    ];  

    // Relación con consumo de agua
    public function consumosAgua()
    {
        return $this->hasMany(ConsumoAgua::class, 'consag_id', 'consag_id');
    }
}
