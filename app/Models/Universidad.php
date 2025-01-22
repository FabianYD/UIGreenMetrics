<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Campus;
use App\Models\Ciudad;
use App\Models\Empleado;

class Universidad extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_UNIVERSIDADES';
    protected $primaryKey = 'UNI_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'UNI_ID',
        'CIU_ID',
        'UNI_NOMBRES'
    ];

    // Relaciones
    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'CIU_ID', 'CIU_ID');
    }

    public function campus()
    {
        return $this->hasMany(Campus::class, 'UNI_ID', 'UNI_ID');
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class, 'UNI_ID', 'UNI_ID');
    }
}
