<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ControlContaminacion extends Model
{
    protected $table = 'GM_WEC_CONTROL_CONTAMINACION';
    protected $primaryKey = 'CONTAM_ID';
    public $timestamps = false;

    protected $fillable = [
        'CAMPUS_ID',
        'CONTAM_TIPO',
        'CONTAM_ESTADO',
        'CONTAM_FECHAINICIO',
        'CONTAM_DESCRIPCION'
    ];

    protected $casts = [
        'CONTAM_FECHAINICIO' => 'date'
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }
}
