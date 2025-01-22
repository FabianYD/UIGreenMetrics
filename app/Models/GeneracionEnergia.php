<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneracionEnergia extends Model
{
    protected $table = 'GM_WEC_GENERACION_ENERGIAS';
    protected $primaryKey = 'GENENE_ID';
    public $timestamps = false;

    protected $fillable = [
        'GENTYPE_ID',
        'FACU_CODIGO',
        'GENENE_TOTAL',
        'GENENE_TIPO',
        'GENENE_FECHA',
        'GENENE_CONSUMO',
    ];

    protected $casts = [
        'GENENE_TOTAL' => 'decimal:2',
        'GENENE_CONSUMO' => 'decimal:2',
        'GENENE_FECHA' => 'date',
    ];

    public function tipoGeneracion(): BelongsTo
    {
        return $this->belongsTo(TipoGeneracionEnergia::class, 'GENTYPE_ID', 'GENTYPE_ID');
    }

    public function facultad(): BelongsTo
    {
        return $this->belongsTo(Facultad::class, 'FACU_CODIGO', 'FACU_CODIGO');
    }
}
