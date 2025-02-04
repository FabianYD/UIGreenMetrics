<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class AguaReciclada extends Model
{
    protected $table = 'GM_WEC_AGUA_RECICLADA';
    protected $primaryKey = 'AGUAREC_ID';
    public $timestamps = false;

    protected static function booted()
    {
        static::creating(function ($aguaReciclada) {
            $tratamiento = $aguaReciclada->tratamiento;
            if ($tratamiento && $tratamiento->TRAGUA_TOTAL > 0) {
                $aguaReciclada->AGUAREC_PORCENTAJE = round(($aguaReciclada->AGUAREC_CANTIDAD / $tratamiento->TRAGUA_TOTAL) * 100, 2);
            }
            
            // Actualizar el porcentaje reciclado del tratamiento
            if ($tratamiento) {
                $totalReciclado = $tratamiento->aguasRecicladas()->sum('AGUAREC_CANTIDAD') + $aguaReciclada->AGUAREC_CANTIDAD;
                $tratamiento->update([
                    'TRAGUA_PORCENTAJE_RECICLADO' => round(($totalReciclado / $tratamiento->TRAGUA_TOTAL) * 100, 2)
                ]);
            }
        });

        static::updating(function ($aguaReciclada) {
            $tratamiento = $aguaReciclada->tratamiento;
            if ($tratamiento && $tratamiento->TRAGUA_TOTAL > 0) {
                $aguaReciclada->AGUAREC_PORCENTAJE = round(($aguaReciclada->AGUAREC_CANTIDAD / $tratamiento->TRAGUA_TOTAL) * 100, 2);
            }
            
            // Actualizar el porcentaje reciclado del tratamiento
            if ($tratamiento) {
                $totalReciclado = $tratamiento->aguasRecicladas()
                    ->where('AGUAREC_ID', '!=', $aguaReciclada->AGUAREC_ID)
                    ->sum('AGUAREC_CANTIDAD') + $aguaReciclada->AGUAREC_CANTIDAD;
                $tratamiento->update([
                    'TRAGUA_PORCENTAJE_RECICLADO' => round(($totalReciclado / $tratamiento->TRAGUA_TOTAL) * 100, 2)
                ]);
            }
        });

        static::deleted(function ($aguaReciclada) {
            $tratamiento = $aguaReciclada->tratamiento;
            if ($tratamiento) {
                $totalReciclado = $tratamiento->aguasRecicladas()->sum('AGUAREC_CANTIDAD');
                $tratamiento->update([
                    'TRAGUA_PORCENTAJE_RECICLADO' => round(($totalReciclado / $tratamiento->TRAGUA_TOTAL) * 100, 2)
                ]);
            }
        });
    }

    protected $fillable = [
        'CAMPUS_ID',
        'AGUAREC_FECHA',
        'AGUAREC_CANTIDAD',
        'AGUAREC_PORCENTAJE',
        'AGUAREC_DESTINO',
        'TRAGUA_ID'
    ];

    protected $casts = [
        'AGUAREC_CANTIDAD' => 'decimal:2',
        'AGUAREC_PORCENTAJE' => 'decimal:2',
        'AGUAREC_FECHA' => 'datetime',
    ];

    public function tratamiento(): BelongsTo
    {
        \Log::info("Obteniendo tratamiento para agua reciclada {$this->AGUAREC_ID}");
        return $this->belongsTo(TratamientoAgua::class, 'TRAGUA_ID', 'TRAGUA_ID');
    }

    public function campus()
    {
        return $this->hasOneThrough(
            Campus::class,
            TratamientoAgua::class,
            'TRAGUA_ID', // Clave foránea en agua_reciclada
            'CAMPUS_ID', // Clave primaria en campus
            'TRAGUA_ID', // Clave local en agua_reciclada
            'CONSAG_ID' // Clave foránea en tratamiento_agua que conecta con consumo
        )->through('tratamiento.consumo.medidorAgua');
    }
}
