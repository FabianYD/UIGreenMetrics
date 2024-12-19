<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedidorAgua extends Model
{
    protected $table = 'gm_wec_medidores_agua';
    protected $primaryKey = 'medag_id'; // Clave primaria
    public $incrementing = false; // No es autoincremental
    protected $keyType = 'string'; // Clave primaria es de tipo string
    public $timestamps = false;

    protected $fillable = [
        'campus_id',
        'medag_fechaadquisicion',
    ];

    /**
     * Relación con el modelo Campus.
     */
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'campus_id', 'campus_id');
    }

    /**
     * Relación con el modelo ConsumoAgua.
     */
    public function consumosAgua()
    {
        return $this->hasMany(ConsumoAgua::class, 'medag_id', 'medag_id');
    }
}
