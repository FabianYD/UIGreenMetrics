<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumoAgua extends Model
{
    protected $table = 'GM_WEC_CONSUMO_AGUA';
    protected $primaryKey = 'CONSAG_ID';
    public $timestamps = false;

    protected $fillable = [
        'MEDAG_ID',
        'MEDIDADAG_COD',
        'CONSAG_TOTAL',
        'CONSENE_FECHAPAGO'
    ];

    // Relaciones
    public function medidor()
    {
        return $this->belongsTo(MedidorAgua::class, 'MEDAG_ID', 'MEDAG_ID');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedidaAgua::class, 'MEDIDADAG_COD', 'MEDIDADAG_COD');
    }
}

class ConsumoEnergia extends Model
{
    protected $table = 'GM_WEC_CONSUMO_ENERGIAS';
    protected $primaryKey = 'CONSENE_ID';
    public $timestamps = false;

    protected $fillable = [
        'IDMEDIDOR2',
        'TIPOENE_ID',
        'MEDENE_COD',
        'CONSENE_TOTAL',
        'CONSENE_FECHAPAGO'
    ];

    // Relaciones
    public function medidor()
    {
        return $this->belongsTo(MedidorElectrico::class, 'IDMEDIDOR2', 'IDMEDIDOR2');
    }

    public function tipoEnergia()
    {
        return $this->belongsTo(TipoEnergia::class, 'TIPOENE_ID', 'TIPOENE_ID');
    }

    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedidaEnergia::class, 'MEDENE_COD', 'MEDENE_COD');
    }
}
