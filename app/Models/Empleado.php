<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

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

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'email', 'EMP_EMAIL');
    }

    public function sincronizarUsuario(?string $password = null): void
    {
        $userData = [
            'name' => $this->EMP_NOMBRES . ' ' . $this->EMP_APELLIDOS,
            'email' => $this->EMP_EMAIL,
        ];

        if ($password) {
            $userData['password'] = Hash::make($password);
        }

        $user = $this->user;

        if ($user) {
            $user->update($userData);
        } else {
            $userData['password'] = Hash::make($password ?? $this->EMP_DNI); // Usar DNI como contraseña por defecto
            User::create($userData);
        }
    }
}
