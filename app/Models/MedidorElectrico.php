<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedidorElectrico extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_medidores_electricos';
    protected $primaryKey = 'IDMEDIDOR2';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IDMEDIDOR2',
        'CAMPUS_ID',
        'MEDAG_FECHAADQUISICION'
    ];

    public function consumosEnergia(): HasMany
    {
        return $this->hasMany(ConsumoEnergia::class, 'IDMEDIDOR2', 'IDMEDIDOR2');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }
}
