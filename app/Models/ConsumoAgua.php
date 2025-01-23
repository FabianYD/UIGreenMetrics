<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConsumoAgua extends Model
{
    protected $table = 'GM_WEC_CONSUMO_AGUA';
    protected $primaryKey = 'CONSAG_ID';
    public $timestamps = false;

    protected $fillable = [
        'CONSAG_ID',
        'MEDAG_ID',
        'MEDIDADAG_COD',
        'CONSAG_TOTAL',
        'CONSENE_FECHAPAGO'
    ];

    protected $casts = [
        'CONSAG_TOTAL' => 'decimal:2',
        'CONSENE_FECHAPAGO' => 'date'
    ];

    public function medidor(): BelongsTo
    {
        return $this->belongsTo(MedidorAgua::class, 'MEDAG_ID', 'MEDAG_ID')
            ->with('campus');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedidaAgua::class, 'MEDIDADAG_COD', 'MEDIDADAG_COD');
    }

    public function costos(): HasOne
    {
        return $this->hasOne(CostoAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }

    public function tratamientos(): HasMany
    {
        return $this->hasMany(TratamientosAgua::class, 'CONSAG_ID', 'CONSAG_ID');
    }

    public function reutilizaciones(): HasMany
    {
        return $this->hasMany(ReutilizacionAguas::class, 'CONSAG_ID', 'CONSAG_ID');
    }
}
