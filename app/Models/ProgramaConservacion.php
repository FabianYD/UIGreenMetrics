<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgramaConservacion extends Model
{
    protected $table = 'GM_WEC_PROGRAMAS_CONSERVACION';
    protected $primaryKey = 'PROGCONS_ID';
    public $timestamps = false;

    protected $fillable = [
        'CAMPUS_ID',
        'PROGCONS_NOMBRE',
        'PROGCONS_DESCRIPCION',
        'PROGCONS_ESTADO',
        'PROGCONS_FECHAINICIO',
        'PROGCONS_FECHAFIN',
        'PROGCONS_AVANCE'
    ];

    protected $casts = [
        'PROGCONS_FECHAINICIO' => 'date',
        'PROGCONS_FECHAFIN' => 'date',
        'PROGCONS_AVANCE' => 'decimal:2'
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }
}
