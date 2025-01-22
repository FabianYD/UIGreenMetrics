<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsumoAgua extends Model
{
    protected $table = 'GM_WEC_CONSUMO_AGUA';
    protected $primaryKey = 'CONSAG_ID';
    public $timestamps = false;

    protected $fillable = [
        'MEDAG_ID',
        'MEDIDADAG_COD',
        'CONSAG_TOTAL',
        'CONSENE_FECHAPAGO',
        'CONSAG_OBSERVACION'
    ];

    protected $casts = [
        'CONSAG_TOTAL' => 'decimal:2',
        'CONSENE_FECHAPAGO' => 'date'
    ];

    public function medidor(): BelongsTo
    {
        return $this->belongsTo(MedidorAgua::class, 'MEDAG_ID', 'MEDAG_ID');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedidaAgua::class, 'MEDIDADAG_COD', 'MEDIDADAG_COD');
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(TratamientoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }

    public function reutilizaciones(): HasMany
    {
        return $this->hasMany(ReutilizacionAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }

    public function costos(): HasMany
    {
        return $this->hasMany(CostoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }
}
