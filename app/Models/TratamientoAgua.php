<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TratamientoAgua extends Model
{
    protected $table = 'GM_WEC_TRATAMIENTOS_AGUAS';
    protected $primaryKey = 'TRAGUA_ID';
    public $timestamps = false;

    protected $fillable = [
        'CONSAG_ID',
        'TIPOTRA_COD',
        'TRAGUA_TOTAL',
        'TRAGUA_PORCENTAJE_TRATADO',
        'TRAGUA_PORCENTAJE_RECICLADO',
        'TRAGUA_ESTADO_PROGRAMA'
    ];

    protected $casts = [
        'TRAGUA_TOTAL' => 'decimal:2',
        'TRAGUA_PORCENTAJE_TRATADO' => 'decimal:2',
        'TRAGUA_PORCENTAJE_RECICLADO' => 'decimal:2',
    ];

    protected static function booted()
    {
        static::creating(function ($tratamiento) {
            $consumo = $tratamiento->consumo;
            if ($consumo && $consumo->CONSAG_TOTAL > 0) {
                $tratamiento->TRAGUA_PORCENTAJE_TRATADO = round(($tratamiento->TRAGUA_TOTAL / $consumo->CONSAG_TOTAL) * 100, 2);
            }
        });

        static::updating(function ($tratamiento) {
            $consumo = $tratamiento->consumo;
            if ($consumo && $consumo->CONSAG_TOTAL > 0) {
                $tratamiento->TRAGUA_PORCENTAJE_TRATADO = round(($tratamiento->TRAGUA_TOTAL / $consumo->CONSAG_TOTAL) * 100, 2);
            }
        });
    }

    public function consumo(): BelongsTo
    {
        return $this->belongsTo(ConsumoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }

    public function tipoTratamiento(): BelongsTo
    {
        return $this->belongsTo(TipoTratamiento::class, 'TIPOTRA_COD', 'TIPOTRA_COD');
    }

    public function aguasRecicladas(): HasMany
    {
        \Log::info("Obteniendo aguas recicladas para tratamiento {$this->TRAGUA_ID}");
        $aguas = $this->hasMany(AguaReciclada::class, 'TRAGUA_ID', 'TRAGUA_ID');
        \Log::info("SQL Query:", ['sql' => $aguas->toSql()]);
        return $aguas;
    }

    public function getTotalRecicladoAttribute()
    {
        $total = $this->aguasRecicladas()->sum('AGUAREC_CANTIDAD');
        \Log::info("Total reciclado para tratamiento {$this->TRAGUA_ID}: {$total}");
        return (float) $total;
    }

    public function getPorcentajeRecicladoAttribute()
    {
        $total = (float) $this->TRAGUA_TOTAL;
        $reciclado = $this->total_reciclado;
        
        \Log::info("Calculando porcentaje para tratamiento {$this->TRAGUA_ID}:");
        \Log::info("Total tratado: {$total}");
        \Log::info("Total reciclado: {$reciclado}");
        
        if ($total <= 0) {
            \Log::info("Total es 0 o menor, retornando 0%");
            return 0;
        }
        
        $porcentaje = round(($reciclado / $total) * 100, 2);
        \Log::info("Porcentaje calculado: {$porcentaje}%");
        return $porcentaje;
    }

    public function updatePorcentajeReciclado()
    {
        $porcentaje = $this->porcentaje_reciclado;
        \Log::info("Actualizando porcentaje en BD para tratamiento {$this->TRAGUA_ID}: {$porcentaje}%");
        $this->update(['TRAGUA_PORCENTAJE_RECICLADO' => $porcentaje]);
    }
}
