<?php

namespace App\Filament\Widgets;

use App\Models\TratamientoAgua;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TiposdeTratamiento extends ChartWidget
{
    protected static ?string $heading = 'Cantidad por Tipo de Tratamiento de Agua';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $data = TratamientoAgua::select(
            'TIPOTRA_COD',
            DB::raw('SUM(TRAGUA_TOTAL) as total_tratamiento')
        )
        ->groupBy('TIPOTRA_COD')
        ->orderBy('total_tratamiento', 'desc')
        ->get();

        $labels = [];
        $totals = [];

        foreach ($data as $record) {
            $tipo = $record->tipoTratamiento->TIPOTRA_NOMBRES ?? $record->TIPOTRA_COD; // Nombre o código
            $labels[] = $tipo;
            $totals[] = $record->total_tratamiento; // Agregar la cantidad en lugar del porcentaje
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Agua (m³)',
                    'data' => $totals,
                    'backgroundColor' => '#4CAF50',
                    'borderColor' => '#388E3C',
                    'borderWidth' => 2,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
