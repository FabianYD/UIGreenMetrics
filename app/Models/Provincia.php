<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Provincia extends Model
{
    protected $table = 'gm_wec_provincias';
    protected $primaryKey = 'PROV_ID';
    public $timestamps = false;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'PROV_ID',
        'PROV_NOMBRES',
        'PROV_CODIGOSPOSTAL'
    ];

    public function ciudades(): HasMany
    {
        return $this->hasMany(Ciudad::class, 'PROV_ID', 'PROV_ID');
    }
}
