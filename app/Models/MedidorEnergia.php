<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedidorEnergia extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_medidores_electricos';
    protected $primaryKey = 'medene_id'; // Llave primaria correcta

    public $incrementing = false; // No es autoincremental

    protected $keyType = 'string'; // Clave primaria es de tipo string

    public $timestamps = false;

    
    protected $fillable = [
        'campus_id',
        'medene_fechaadquisicion',
    ];

    /**
     * Relación con Campus
     */
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'campus_id');
    }

    public function consumosEnergia()
    {
        return $this->hasMany(ConsumoEnergia::class, 'medene_id', 'medene_id');
    }
}
