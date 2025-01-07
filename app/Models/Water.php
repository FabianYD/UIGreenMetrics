<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Water extends Model
{
    use HasFactory;

    protected $fillable = [
        'medidor_id',
        'consumo_total',
        'fecha_pago',
        'descripcion',
        'ubicacion',
        'tipo_consumo'
    ];

    protected $casts = [
        'consumo_total' => 'decimal:2',
        'fecha_pago' => 'date'
    ];
}
