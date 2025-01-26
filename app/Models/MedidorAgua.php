<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedidorAgua extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_medidores_agua';
    protected $primaryKey = 'MEDAG_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'MEDAG_ID',
        'CAMPUS_ID',
        'MEDAG_FECHAADQUISICION'
    ];

    public function consumosAgua(): HasMany
    {
        return $this->hasMany(ConsumoAgua::class, 'MEDAG_ID', 'MEDAG_ID');
    }

    public function campus(): BelongsTo
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }
}
