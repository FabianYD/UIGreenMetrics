<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Universidad;
use App\Models\Facultad;
use App\Models\MedidorAgua;
use App\Models\MedidorElectrico;

class Campus extends Model
{
    protected $table = 'GM_WEC_CAMPUS';
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
}
