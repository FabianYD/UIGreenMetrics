<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReutilizacionAgua extends Model
{
    protected $table = 'gm_wec_reutilizacion_aguas';
    protected $primaryKey = 'reuag_id';
    public $timestamps = false;

    protected $fillable = [
        'consag_id',
        'reuag_detalle',
        'reuag_fecha',
        'reuag_cantidad',
        'reuag_sostenibilidad',
    
    ];

    // Relación inversa con ConsumoAgua
    public function consumoAgua()
    {
        return $this->belongsTo(ConsumoAgua::class, 'consag_id', 'consag_id');
    }

}
