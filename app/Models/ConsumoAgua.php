<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumoAgua extends Model
{
    protected $table = 'gm_wec_consumo_agua';
    protected $primaryKey = 'consag_id';
    public $timestamps = false;

    protected $fillable = [
        'medag_id',
        'consag_total',
        'consene_fechapago',
        'consag_id', // La columna que se relaciona con la unidad de medida
    ];

    // Relación con el medidor de agua
    public function medidorAgua()
    {
        return $this->belongsTo(MedidorAgua::class, 'medag_id', 'medag_id');
    }

    // Relación con la tabla de tipos de unidad de medida de agua
    public function unidadMedida()
    {
        return $this->belongsTo(TypeUnidadMedAgua::class, 'consag_id', 'consag_id');
    }

     // Relación con reutilización de agua
     public function reutilizacionAgua()
{
    return $this->hasOne(ReutilizacionAgua::class, 'consag_id', 'consag_id');
}


}
