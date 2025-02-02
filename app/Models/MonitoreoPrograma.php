<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MonitoreoPrograma extends Model
{
    protected $table = 'GM_WEC_MONITOREO_PROGRAMAS';
    protected $primaryKey = 'MONIT_ID';
    public $timestamps = false;

    protected $fillable = [
        'CAMPUS_ID',
        'EMP_DNI',
        'MONIT_TIPO',
        'MONIT_FECHA',
        'MONIT_METRICAS',
        'MONIT_RESULTADOS',
        'MONIT_USO_TIC'
    ];

    protected $casts = [
        'MONIT_FECHA' => 'date',
        'MONIT_METRICAS' => 'json',
        'MONIT_USO_TIC' => 'boolean',
        'MONIT_TIPO' => 'string'
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class, 'EMP_DNI', 'EMP_DNI');
    }
}
