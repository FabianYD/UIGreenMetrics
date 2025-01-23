<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConsumoEnergia extends Model
{
    protected $table = 'GM_WEC_CONSUMO_ENERGIAS';
    protected $primaryKey = 'CONSENE_ID';
    public $timestamps = false;

    protected $fillable = [
        'IDMEDIDOR2',
        'TIPOENE_ID',
        'MEDENE_COD',
        'CONSENE_TOTAL',
        'CONSENE_FECHAPAGO'
    ];

    protected $casts = [
        'CONSENE_TOTAL' => 'decimal:2',
        'CONSENE_FECHAPAGO' => 'date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($consumo) {
            $consumo->costos()->delete();
        });
    }

    public function medidor(): BelongsTo
    {
        return $this->belongsTo(MedidorElectrico::class, 'IDMEDIDOR2', 'IDMEDIDOR2')
            ->with('campus');
    }

    public function tipoEnergia(): BelongsTo
    {
        return $this->belongsTo(TipoEnergia::class, 'TIPOENE_ID', 'TIPOENE_ID');
    }

    public function unidadMedida(): BelongsTo
    {
        return $this->belongsTo(UnidadMedidaEnergia::class, 'MEDENE_COD', 'MEDENE_COD');
    }

    public function costos(): HasOne
    {
        return $this->hasOne(CostoEnergia::class, 'CONSENE_ID', 'CONSENE_ID');
    }
}
