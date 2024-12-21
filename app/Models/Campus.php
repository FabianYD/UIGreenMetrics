<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    protected $table = 'gm_wec_campus';
    protected $primaryKey = 'campus_id';
    public $incrementing = false; // Clave primaria no es autoincremental
    protected $keyType = 'string'; // Definir el tipo como string
    public $timestamps = false;

    protected $fillable = [
        'uni_id',
        'campus_nombres',
        'campus_calleprincipal',
        'campus_callesecundaria',
    ];

    // Relación con la universidad
    public function universidad()
    {
        return $this->belongsTo(Universidad::class, 'uni_id', 'uni_id');
    }

    // Relación con los medidores de agua
    public function medidoresAgua()
    {
        return $this->hasMany(MedidorAgua::class, 'campus_id', 'campus_id');
    }

    public function medidoresEnergia()
    {
        return $this->hasMany(MedidorEnergia::class, 'campus_id', 'campus_id');
    }

    public function facultades()
    {
        return $this->hasMany(Facultad::class, 'campus_id', 'campus_id');
    }

}
