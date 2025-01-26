<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsumoAgua extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_consumo_agua';
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
        'CONSENE_FECHAPAGO' => 'date',
        'CONSAG_TOTAL' => 'decimal:2'
    ];

    public function medidorAgua(): BelongsTo
    {
        return $this->belongsTo(MedidorAgua::class, 'MEDAG_ID', 'MEDAG_ID');
    }
}
