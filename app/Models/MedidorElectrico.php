<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedidorElectrico extends Model
{
    protected $table = 'GM_WEC_MEDIDORES_ELECTRICOS';
    protected $primaryKey = 'IDMEDIDOR2';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IDMEDIDOR2',
        'CAMPUS_ID',
        'MEDAG_FECHAADQUISICION'
    ];

    protected $casts = [
        'MEDAG_FECHAADQUISICION' => 'date'
    ];

    protected $with = ['campus'];

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function consumos(): HasMany
    {
        return $this->hasMany(ConsumoEnergia::class, 'IDMEDIDOR2', 'IDMEDIDOR2');
    }
}
