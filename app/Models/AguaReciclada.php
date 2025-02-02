<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AguaReciclada extends Model
{
    protected $table = 'GM_WEC_AGUA_RECICLADA';
    protected $primaryKey = 'AGUAREC_ID';
    public $timestamps = false;

    protected $fillable = [
        'CAMPUS_ID',
        'AGUAREC_FECHA',
        'AGUAREC_CANTIDAD',
        'AGUAREC_METODO',
        'AGUAREC_DESTINO'
    ];

    protected $casts = [
        'AGUAREC_FECHA' => 'date',
        'AGUAREC_CANTIDAD' => 'decimal:2'
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }
}
