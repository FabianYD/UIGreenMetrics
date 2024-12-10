<?php

namespace App\Http\Controllers;

use App\Models\WaterCollectionPoint;
use App\Services\WaterStatisticsService;
use Illuminate\Http\Request;

class WaterCollectionPointController extends Controller
{
    protected $statisticsService;

    public function __construct(WaterStatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index()
    {
        $points = WaterCollectionPoint::all();
        $statistics = $this->statisticsService->calculateStatistics();
        return view('water-points.index', compact('points', 'statistics'));
    }

    public function create()
    {
        return view('water-points.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'capacidad' => 'required|numeric|min:0',
            'agua_tratada' => 'required|numeric|min:0',
            'agua_reciclada' => 'required|numeric|min:0',
            'agua_reutilizada' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo'
        ]);

        WaterCollectionPoint::create($validated);

        return redirect()->route('water-points.index')
            ->with('success', 'Punto de recolección creado exitosamente.');
    }

    public function edit(WaterCollectionPoint $waterPoint)
    {
        return view('water-points.edit', compact('waterPoint'));
    }

    public function update(Request $request, WaterCollectionPoint $waterPoint)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'capacidad' => 'required|numeric|min:0',
            'agua_tratada' => 'required|numeric|min:0',
            'agua_reciclada' => 'required|numeric|min:0',
            'agua_reutilizada' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,inactivo'
        ]);

        $waterPoint->update($validated);

        return redirect()->route('water-points.index')
            ->with('success', 'Punto de recolección actualizado exitosamente.');
    }

    public function destroy(WaterCollectionPoint $waterPoint)
    {
        $waterPoint->delete();

        return redirect()->route('water-points.index')
            ->with('success', 'Punto de recolección eliminado exitosamente.');
    }
}
