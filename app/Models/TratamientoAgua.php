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
    ];

    protected $casts = [
        'TRAGUA_TOTAL' => 'decimal:2',
    ];

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
        return $this->hasMany(AguaReciclada::class, 'TRAGUA_ID', 'TRAGUA_ID');
    }
}
