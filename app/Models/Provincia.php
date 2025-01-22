<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provincia extends Model
{
    use HasFactory;

    protected $table = 'GM_WEC_PROVINCIAS';
    protected $primaryKey = 'PROV_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'PROV_ID',
        'PROV_NOMBRES',
        'PROV_CODIGOSPOSTAL'
    ];
}
