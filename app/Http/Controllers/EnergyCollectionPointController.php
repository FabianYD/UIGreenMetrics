<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\ConsumoEnergia;
use App\Models\GeneracionEnergia;

class EnergyCollectionPointController extends Controller
{
    public function index()
{
    // Obtener las generaciones de energía con sus relaciones necesarias
    $generaciones = GeneracionEnergia::with([
        'facultad.campus.universidad' // Relación de generación con facultad, campus y universidad
    ])->get();

    // Obtener los consumos de energía con las relaciones necesarias
    $consumos = ConsumoEnergia::with([
        'tipoEnergia', 
        'unidadMedida', 
        'medidorEnergia.campus.universidad'
    ])->get();

    // Retornar las variables necesarias a la vista
    return view('energy-points.sostenibilidadEnergia', compact('generaciones', 'consumos'));
}
    public function calcularSostenibilidadEnergia()
{
    $consumos = ConsumoEnergia::with(['tipoEnergia'])->get();

    foreach ($consumos as $consumo) {
        // Validar que consene_total no sea cero para evitar división por cero
        if ($consumo->consene_total <= 0) {
            $consumo->update([
                'sostenibilidadene' => 0, // Asignar sostenibilidad 0 si los datos no son válidos
            ]);
            continue;
        }

        // Asignar peso según el tipo de energía
        $peso = 0;
        switch (strtolower($consumo->tipoEnergia->tipoene_nombres)) {
            case 'solar':
                $peso = 95; // Alta sostenibilidad
                break;
            case 'hidroeléctrica':
                $peso = 78; // Moderada sostenibilidad
                break;
            case 'termoeléctrica':
                $peso = 30; // Baja sostenibilidad
                break;
            default:
                $peso = 0; // Peso genérico si el tipo no coincide
                break;
        }

        // Calcular sostenibilidad en porcentaje
        $sostenibilidad = ($peso / 100) * (1000 / $consumo->consene_total);
        $sostenibilidad = min(round($sostenibilidad * 100), 100); // Limitar a un máximo de 100%

        // Actualizar el valor en la base de datos
        $consumo->update([
            'sostenibilidadene' => $sostenibilidad,
        ]);
    }

    return redirect()->route('energy-points.SostenibilidadEnergia');
}
}