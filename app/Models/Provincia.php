<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'gm_wec_provincias';
    protected $primaryKey = 'prov_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'prov_nombres',
        'prov_codigospostal',
    ];

    // Relación con ciudades
    public function ciudades()
    {
        return $this->hasMany(Ciudadd::class, 'prov_id', 'prov_id');
    }
}
