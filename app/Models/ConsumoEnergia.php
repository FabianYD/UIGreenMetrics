<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne; // O HasMany si es necesario

class ConsumoEnergia extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_consumo_energias';
    protected $primaryKey = 'CONSENE_ID';
    public $timestamps = false;

    protected $fillable = [
        'CONSENE_ID',
        'IDMEDIDOR2',
        'TIPOENE_ID',
        'MEDENE_COD',
        'CONSENE_TOTAL',
        'CONSENE_FECHAPAGO'
    ];

    protected $casts = [
        'CONSENE_FECHAPAGO' => 'date',
        'CONSENE_TOTAL' => 'decimal:2'
    ];

    public function medidorElectrico(): BelongsTo
    {
        return $this->belongsTo(MedidorElectrico::class, 'IDMEDIDOR2', 'IDMEDIDOR2');
    }

    public function unidadMedidaEnergia(): BelongsTo  // Añadido 'BelongsTo' para tipo de relación explícito
    {
        return $this->belongsTo(UnidadMedidaEnergia::class, 'MEDENE_COD', 'MEDENE_COD');
    }

    public function tipoEnergia(): BelongsTo
    {
        return $this->belongsTo(TipoEnergia::class, 'TIPOENE_ID', 'TIPOENE_ID');
    }
    
    public function costos(): HasOne // O HasMany si corresponde
    {
        return $this->hasOne(CostoEnergia::class, 'CONSENE_ID', 'CONSENE_ID'); // Relación con 'COSTENE_ID' en CostoEnergia
    }

}
