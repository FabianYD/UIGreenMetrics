<?php

namespace App\Http\Controllers;

use App\Models\TratamientoAgua;
use App\Models\TipoTratamiento;
use App\Models\ConsumoAgua;
use App\Models\MedidorAgua;
use App\Models\Campus;
use App\Models\ReutilizacionAgua;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WaterCollectionPointController extends Controller
{
    /**
     * Muestra la lista de puntos de recolección con datos calculados.
     */
    public function index()
{
    // Ejecutar los cálculos
    $this->calcularSostenibilidad();
    $this->calcularSostenibilidadReciclada();

    // Obtener datos actualizados
    $tratamientos = TratamientoAgua::with([
        'tipoTratamiento',
        'consumoAgua.medidorAgua.campus.universidad',
        'consumoAgua.unidadMedida'
    ])->get();

    $reutilizaciones = ReutilizacionAgua::with('consumoAgua.medidorAgua.campus.universidad')->get();

    return view('water-points.sostenibilidadAgua', compact('tratamientos', 'reutilizaciones'));
}

    /**
     * Calcula la sostenibilidad y eficiencia para cada tratamiento de agua.
     */
    public function calcularSostenibilidad()
    {
        Log::info('Método calcularSostenibilidad ejecutado.');

        $tratamientos = TratamientoAgua::with('tipoTratamiento')->get();

        foreach ($tratamientos as $tratamiento) {
            $porcentajePurificacion = $tratamiento->tipoTratamiento->porcentaje_purificacion / 100;
            $cantidadSostenible = $tratamiento->tragua_total * $porcentajePurificacion;
            $eficiencia = ($tratamiento->tragua_total > 0) ? ($cantidadSostenible / $tratamiento->tragua_total) * 100 : 0;

            $tratamiento->update([
                'cantidad_sostenible' => $cantidadSostenible,
                'eficiencia' => $eficiencia,
            ]);

            Log::info("Tratamiento ID {$tratamiento->id}: Sostenibilidad calculada.");
        }
    }

    /**
     * Calcula la sostenibilidad del agua reciclada.
     */
    public function calcularSostenibilidadReciclada()
    {
        Log::info('Método calcularSostenibilidadReciclada iniciado.');
    
        $reutilizaciones = ReutilizacionAgua::with('consumoAgua')->get();
    
        foreach ($reutilizaciones as $reutilizacion) {
            if ($reutilizacion->consumoAgua) {
                $aguaConsumida = $reutilizacion->consumoAgua->consag_total;
                $aguaReciclada = $reutilizacion->reuag_cantidad;
    
                $sostenibilidad = ($aguaConsumida > 0) ? ($aguaReciclada / $aguaConsumida) * 100 : 0;
    
                $reutilizacion->update([
                    'reuag_sostenibilidad' => round($sostenibilidad, 2),
                ]);
    
                Log::info("Reutilización ID {$reutilizacion->id}: Sostenibilidad calculada como {$sostenibilidad}%.");
            } else {
                Log::warning("Reutilización ID {$reutilizacion->id}: No se encontró un consumo de agua asociado.");
            }
        }
    
        Log::info('Método calcularSostenibilidadReciclada finalizado.');
    }
    /**
     * Muestra el formulario para crear un nuevo punto de recolección.
     */
    public function create()
    {
        return view('water-points.create');
    }

    /**
     * Almacena un nuevo punto de recolección en la base de datos.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'consag_id' => 'required|integer|exists:gm_wec_consumo_agua,consag_id',
            'tipotra_cod' => 'required|string|exists:gm_wec_tipos_tratamientos,tipotra_cod',
            'tragua_total' => 'required|numeric|min:0',
        ]);

        TratamientoAgua::create($validated);

        return redirect()->route('water-points.index')->with('success', 'Punto de recolección creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un punto de recolección existente.
     */
    public function edit(TratamientoAgua $tratamiento)
    {
        return view('water-points.edit', compact('tratamiento'));
    }

    /**
     * Actualiza un punto de recolección existente en la base de datos.
     */
    public function update(Request $request, TratamientoAgua $tratamiento)
    {
        $validated = $request->validate([
            'tragua_total' => 'required|numeric|min:0',
        ]);

        $tratamiento->update($validated);

        return redirect()->route('water-points.index')->with('success', 'Punto de recolección actualizado correctamente.');
    }

    /**
     * Elimina un punto de recolección de la base de datos.
     */
    public function destroy(TratamientoAgua $tratamiento)
    {
        $tratamiento->delete();

        return redirect()->route('water-points.index')->with('success', 'Punto de recolección eliminado correctamente.');
    }
}
