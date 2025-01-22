<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostoAgua extends Model
{
    protected $table = 'GM_WEC_COSTOS_AGUA';
    protected $primaryKey = 'COSTAG_ID';
    public $timestamps = false;

    protected $fillable = [
        'CONSAG_ID',
        'COSTAG_VALORAGREGADO',
        'COSTENE_SUBTOTAL',
        'COSTOAG_IVA',
        'COSTOAG_TOTAL'
    ];

    protected $casts = [
        'COSTAG_VALORAGREGADO' => 'decimal:2',
        'COSTENE_SUBTOTAL' => 'decimal:2',
        'COSTOAG_IVA' => 'decimal:2',
        'COSTOAG_TOTAL' => 'decimal:2'
    ];

    public function consumo(): BelongsTo
    {
        return $this->belongsTo(ConsumoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }
}
