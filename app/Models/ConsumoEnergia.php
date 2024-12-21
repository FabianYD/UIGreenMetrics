<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsumoEnergia extends Model
{
    use HasFactory;

    protected $table = 'gm_wec_consumo_energias';
    protected $primaryKey = 'consene_id'; // Clave primaria correcta
    public $timestamps = false;

    protected $fillable = [
        'medene_id', // Relación con Medidor Electrico
        'tipoene_id', // Relación con Tipo de Energía
        'sostenibilidadene',
        'consene_total', // Consumo total de energía
        'consene_fechapago', // Fecha de pago
    ];

    /**
     * Relación con el medidor de energía.
     */
    public function medidorEnergia()
    {
        return $this->belongsTo(MedidorEnergia::class, 'medene_id', 'medene_id');
    }

    /**
     * Relación con el tipo de energía.
     */
    public function tipoEnergia()
    {
        return $this->belongsTo(TipoEnergia::class, 'tipoene_id', 'tipoene_id');
    }

    /**
     * Relación con la unidad de medida de energía.
     */
    public function unidadMedida()
    {
        return $this->belongsTo(UnidadMedidaEnergia::class, 'consene_id', 'consene_id');
    }
    
}
