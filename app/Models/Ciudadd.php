<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudadd extends Model
{
    protected $table = 'gm_wec_ciudades';
    protected $primaryKey = 'ciu_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'prov_id',
        'ciu_nombres',
        'ciu_codigopostal',
    ];

    // Relación con provincias
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'prov_id', 'prov_id');
    }

    // Relación con universidades
    public function universidades()
    {
        return $this->hasMany(Universidad::class, 'ciu_id', 'ciu_id');
    }
}
