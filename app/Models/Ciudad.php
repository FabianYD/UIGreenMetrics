<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ciudad extends Model
{
    protected $table = 'gm_wec_ciudades';
    protected $primaryKey = 'CIU_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CIU_ID',
        'PROV_ID',
        'CIU_NOMBRES',
        'CIU_CODIGOSPOSTAL'
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(Provincia::class, 'PROV_ID', 'PROV_ID');
    }

    public function universidades(): HasMany
    {
        return $this->hasMany(Universidad::class, 'CIU_ID', 'CIU_ID');
    }
}
