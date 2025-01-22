<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReutilizacionAgua extends Model
{
    protected $table = 'GM_WEC_REUTILIZACION_AGUAS';
    protected $primaryKey = 'REUAG_ID';
    public $timestamps = false;

    protected $fillable = [
        'CONSAG_ID',
        'REUAG_DETALLE',
        'REUAG_FECHA',
        'REUAG_CANTIDAD'
    ];

    protected $casts = [
        'REUAG_FECHA' => 'date',
        'REUAG_CANTIDAD' => 'decimal:2'
    ];

    public function consumo(): BelongsTo
    {
        return $this->belongsTo(ConsumoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }
}
