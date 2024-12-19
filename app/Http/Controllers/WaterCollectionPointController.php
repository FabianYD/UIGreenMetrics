<?php

namespace App\Http\Controllers;

use App\Models\WaterCollectionPoint;
use App\Services\WaterStatisticsService;

use App\Models\TratamientoAgua;
use App\Models\TipoTratamiento;
use App\Models\ConsumoAgua;
use App\Models\MedidorAgua;
use App\Models\Campus;
use Illuminate\Http\Request;

class WaterCollectionPointController extends Controller
{
    /**
     * Muestra la lista de puntos de recolección con datos calculados.
     */
    public function index()
{
    $tratamientos = TratamientoAgua::with([
        'tipoTratamiento',
        'consumoAgua.medidorAgua.campus.universidad',
        'consumoAgua.unidadMedida' // Asegúrate de incluir esta relación
    ])->get();

    return view('water-points.index', compact('tratamientos'));
}


    /**
     * Calcula la sostenibilidad y eficiencia para cada tratamiento de agua.
     */
    public function calcularSostenibilidad()
    {
        $tratamientos = TratamientoAgua::with('tipoTratamiento')->get();

        foreach ($tratamientos as $tratamiento) {
            $porcentajePurificacion = $tratamiento->tipoTratamiento->porcentaje_purificacion / 100;
            $cantidadSostenible = $tratamiento->tragua_total * $porcentajePurificacion;
            $eficiencia = ($tratamiento->tragua_total > 0) ? ($cantidadSostenible / $tratamiento->tragua_total) * 100 : 0;

            // Actualizar los valores en la base de datos
            $tratamiento->update([
                'cantidad_sostenible' => $cantidadSostenible,
                'eficiencia' => $eficiencia,
            ]);
        }

        return redirect()->route('water-points.index')->with('success', 'Cálculos de sostenibilidad actualizados correctamente.');
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
