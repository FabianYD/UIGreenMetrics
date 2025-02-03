<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Campus;
use App\Models\GeneracionEnergia;

class Facultad extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_FACULTADES';
    protected $primaryKey = 'FACU_CODIGO';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'FACU_CODIGO',
        'CAMPUS_ID',
        'FACU_NOMBRE'
    ];

    // Relaciones
    public function campus()
    {
        return $this->belongsTo(Campus::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function generacionEnergias()
    {
        return $this->hasMany(GeneracionEnergia::class, 'FACU_CODIGO', 'FACU_CODIGO');
    }
    public function consumosAgua()
   {
    return $this->hasMany(ConsumoAgua::class, 'FACU_CODIGO', 'FACU_CODIGO');
   }

   public function consumosEnergia()
   {
    return $this->hasMany(ConsumoEnergia::class, 'FACU_CODIGO', 'FACU_CODIGO');
   }
}
