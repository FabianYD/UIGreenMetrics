<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Universidad extends Model
{
    protected $table = 'gm_wec_universidades';
    protected $primaryKey = 'uni_id';
    public $incrementing = false; // Clave primaria no es autoincremental
    protected $keyType = 'string'; // Definir el tipo como string
    public $timestamps = false;

    protected $fillable = [
        'ciu_id',
        'uni_nombres',
    ];

    // Relación con la ciudad
    public function ciudad()
    {
        return $this->belongsTo(Ciudadd::class, 'ciu_id', 'ciu_id');
    }

    // Relación con los campus
    public function campus()
    {
        return $this->hasMany(Campus::class, 'uni_id', 'uni_id');
    }
}
