<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CostoEnergia extends Model
{
    protected $table = 'GM_WEC_COSTOS_ENERGIAS';
    protected $primaryKey = 'COSTENE_ID';
    public $timestamps = false;

    protected $fillable = [
        'CONSENE_ID',
        'COSTENE_VALORCONS',
        'COSTENE_SUBSIDIO',
        'COSTENE_SUBTOTAL',
        'COSTENE_SUBTOTAL_ALUM_PUBLIC',
        'COSTENE_BASEIVA',
        'COSTENE_TOTAL'
    ];

    protected $casts = [
        'COSTENE_VALORCONS' => 'decimal:2',
        'COSTENE_SUBSIDIO' => 'decimal:2',
        'COSTENE_SUBTOTAL' => 'decimal:2',
        'COSTENE_SUBTOTAL_ALUM_PUBLIC' => 'decimal:2',
        'COSTENE_BASEIVA' => 'decimal:2',
        'COSTENE_TOTAL' => 'decimal:2'
    ];

    public function consumo(): BelongsTo
    {
        return $this->belongsTo(ConsumoEnergia::class, 'CONSENE_ID', 'CONSENE_ID');
    }
}
