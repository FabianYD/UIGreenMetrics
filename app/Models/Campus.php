<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Campus extends Model
{
    protected $table = 'gm_wec_campus';
    protected $primaryKey = 'CAMPUS_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'CAMPUS_ID',
        'UNI_ID',
        'CAMPUS_NOMBRES',
        'CAMPUS_CALLEPRINCIPAL',
        'CAMPUS_CALLESECUNDARIA'
    ];

    public function universidad(): BelongsTo
    {
        return $this->belongsTo(Universidad::class, 'UNI_ID', 'UNI_ID');
    }

    public function medidoresElectricos(): HasMany
    {
        return $this->hasMany(MedidorElectrico::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function medidoresAgua(): HasMany
    {
        return $this->hasMany(MedidorAgua::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function facultades(): HasMany
    {
        return $this->hasMany(Facultad::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    // Métodos auxiliares para obtener consumos
    public function consumosAgua()
    {
        return $this->hasManyThrough(
            ConsumoAgua::class,
            MedidorAgua::class,
            'CAMPUS_ID',
            'MEDAG_ID',
            'CAMPUS_ID',
            'MEDAG_ID'
        );
    }

    public function consumosEnergia()
    {
        return $this->hasManyThrough(
            ConsumoEnergia::class,
            MedidorElectrico::class,
            'CAMPUS_ID',
            'IDMEDIDOR2',
            'CAMPUS_ID',
            'IDMEDIDOR2'
        );
    }
}
