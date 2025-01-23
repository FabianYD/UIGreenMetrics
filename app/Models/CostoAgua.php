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

    // Asegurarse de que los valores nulos se conviertan a 0
    protected function setValorAgregadoAttribute($value)
    {
        $this->attributes['COSTAG_VALORAGREGADO'] = $value ?? 0;
    }

    protected function setSubtotalAttribute($value)
    {
        $this->attributes['COSTENE_SUBTOTAL'] = $value ?? 0;
    }

    protected function setIvaAttribute($value)
    {
        $this->attributes['COSTOAG_IVA'] = $value ?? 0;
    }

    protected function setTotalAttribute($value)
    {
        $this->attributes['COSTOAG_TOTAL'] = $value ?? 0;
    }

    // Asegurarse de que los valores nulos se lean como 0
    public function getValorAgregadoAttribute($value)
    {
        return $value ?? 0;
    }

    public function getSubtotalAttribute($value)
    {
        return $value ?? 0;
    }

    public function getIvaAttribute($value)
    {
        return $value ?? 0;
    }

    public function getTotalAttribute($value)
    {
        return $value ?? 0;
    }

    public function consumo(): BelongsTo
    {
        return $this->belongsTo(ConsumoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }
}
