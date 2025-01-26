<?php

namespace App\Filament\Widgets;

use App\Models\TratamientoAgua;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class TiposdeTratamiento extends ChartWidget
{
    protected static ?string $heading = 'Porcentaje por Tipo de Tratamiento de Agua';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $total = TratamientoAgua::sum('TRAGUA_TOTAL');

        $data = TratamientoAgua::select(
            'TIPOTRA_COD',
            DB::raw('SUM(TRAGUA_TOTAL) as total_tratamiento')
        )
        ->groupBy('TIPOTRA_COD')
        ->orderBy('total_tratamiento', 'desc')
        ->get();

        $labels = [];
        $percentages = [];

        foreach ($data as $record) {
            $tipo = $record->tipoTratamiento->TIPOTRA_NOMBRES ?? $record->TIPOTRA_COD; // Nombre o código
            $labels[] = $tipo;
            $percentages[] = round(($record->total_tratamiento / $total) * 100, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Porcentaje (%)',
                    'data' => $percentages,
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
