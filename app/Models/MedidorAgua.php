<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedidorAgua extends Model
{
    protected $table = 'GM_WEC_MEDIDORES_AGUA';
    protected $primaryKey = 'MEDAG_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'MEDAG_ID',
        'CAMPUS_ID',
        'MEDAG_FECHAADQUISICION'
    ];

    protected $casts = [
        'MEDAG_FECHAADQUISICION' => 'date'
    ];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function consumos(): HasMany
    {
        return $this->hasMany(ConsumoAgua::class, 'MEDAG_ID', 'MEDAG_ID');
    }
}
