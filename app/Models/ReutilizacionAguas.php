<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReutilizacionAguas extends Model
{
    protected $table = 'GM_WEC_REUTILIZACION_AGUAS';
    protected $primaryKey = 'REUAG_ID';
    public $timestamps = false;

    protected $fillable = [
        'REUAG_ID',
        'CONSAG_ID',
        'REUAG_DETALLE',
        'REUAG_FECHA',
        'REUAG_CANTIDAD'
    ];

    protected $casts = [
        'REUAG_CANTIDAD' => 'decimal:2',
        'REUAG_FECHA' => 'date'
    ];

    public function consumo(): BelongsTo
    {
        return $this->belongsTo(ConsumoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }
}
