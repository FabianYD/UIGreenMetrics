<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DispositivoEficiente extends Model
{
    protected $table = 'GM_WEC_DISPOSITIVOS_EFICIENTES';
    protected $primaryKey = 'DISPEF_ID';
    public $timestamps = false;

    protected $fillable = [
        'CAMPUS_ID',
        'FACU_CODIGO',
        'DISPEF_TIPO',
        'DISPEF_UBICACION',
        'DISPEF_EFICIENCIA',
        'DISPEF_FECHAINSTALACION'
    ];

    protected $casts = [
        'DISPEF_FECHAINSTALACION' => 'date',
        'DISPEF_EFICIENCIA' => 'decimal:2'
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function facultad(): BelongsTo
    {
        return $this->belongsTo(Facultad::class, 'FACU_CODIGO', 'FACU_CODIGO');
    }
}
