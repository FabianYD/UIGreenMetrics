<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
