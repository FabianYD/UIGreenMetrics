<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Energy extends Model
{
    use HasFactory;

    protected $fillable = [
        'medidor_id',
        'consumo',
        'tipo_energia',
        'fecha_registro',
        'ubicacion',
        'descripcion'
    ];

    protected $casts = [
        'consumo' => 'decimal:2',
        'fecha_registro' => 'date'
    ];

    public static function getTiposEnergia(): array
    {
        return [
            'Generada' => 'Energía Generada',
            'Consumida' => 'Energía Consumida'
        ];
    }
}
