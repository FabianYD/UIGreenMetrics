<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Empleado extends Model
{
    protected $table = 'GM_WEC_EMPLEADOS';
    protected $primaryKey = 'EMP_DNI';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'EMP_DNI',
        'ROL_COD',
        'UNI_ID',
        'EMP_CODIGO',
        'EMP_NOMBRES',
        'EMP_APELLIDOS',
        'EMP_EMAIL',
    ];

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class, 'ROL_COD', 'ROL_COD');
    }

    public function universidad(): BelongsTo
    {
        return $this->belongsTo(Universidad::class, 'UNI_ID', 'UNI_ID');
    }
}
