<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Campus;
use App\Models\Ciudad;
use App\Models\Empleado;

class Universidad extends Model
{
    protected $table = 'GM_WEC_UNIVERSIDADES';
    protected $primaryKey = 'UNI_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'UNI_ID',
        'CIU_ID',
        'UNI_NOMBRES'
    ];

    // Relaciones
    public function ciudad(): BelongsTo
    {
        return $this->belongsTo(Ciudad::class, 'CIU_ID', 'CIU_ID');
    }

    public function campus(): HasMany
    {
        return $this->hasMany(Campus::class, 'UNI_ID', 'UNI_ID');
    }

    public function empleados(): HasMany
    {
        return $this->hasMany(Empleado::class, 'UNI_ID', 'UNI_ID');
    }
}
