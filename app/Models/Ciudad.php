<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Provincia;
use App\Models\Universidad;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_CIUDADES';
    protected $primaryKey = 'CIU_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'CIU_ID',
        'PROV_ID',
        'CIU_NOMBRES',
        'CIU_CODIGOSPOSTAL'
    ];

    // Relaciones
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'PROV_ID', 'PROV_ID');
    }

    public function universidades()
    {
        return $this->hasMany(Universidad::class, 'CIU_ID', 'CIU_ID');
    }
}
