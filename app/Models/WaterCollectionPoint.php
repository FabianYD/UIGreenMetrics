<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaterCollectionPoint extends Model
{
    protected $fillable = [
        'nombre',
        'ubicacion',
        'capacidad',
        'agua_tratada',
        'agua_reciclada',
        'agua_reutilizada',
        'estado',
        'eficiencia'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::saving(function ($point) {
            if ($point->agua_tratada > 0) {
                $point->eficiencia = (($point->agua_reciclada + $point->agua_reutilizada) / $point->agua_tratada) * 100;
            }
        });
    }
}
