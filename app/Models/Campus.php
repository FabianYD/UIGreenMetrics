<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Universidad;
use App\Models\Facultad;
use App\Models\MedidorAgua;
use App\Models\MedidorElectrico;

class Campus extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_CAMPUS';
    protected $primaryKey = 'CAMPUS_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'CAMPUS_ID',
        'UNI_ID',
        'CAMPUS_NOMBRES',
        'CAMPUS_CALLEPRINCIPAL',
        'CAMPUS_CALLESECUNDARIA'
    ];

    // Relaciones
    public function universidad()
    {
        return $this->belongsTo(Universidad::class, 'UNI_ID', 'UNI_ID');
    }

    public function facultades()
    {
        return $this->hasMany(Facultad::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function medidoresAgua()
    {
        return $this->hasMany(MedidorAgua::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }

    public function medidoresElectricos()
    {
        return $this->hasMany(MedidorElectrico::class, 'CAMPUS_ID', 'CAMPUS_ID');
    }
}
