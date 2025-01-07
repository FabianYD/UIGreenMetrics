<?php

namespace App\Filament\Widgets;

use App\Models\Water;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class WaterSustainabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Sostenibilidad del Agua';
    protected static ?int $sort = 2;
    protected static ?string $pollingInterval = '300s';

    protected function getData(): array
    {
        return Cache::remember('water_sustainability_data', 300, function() {
            // Obtener datos del año actual
            $waterData = Water::whereYear('fecha_pago', now()->year)
                ->selectRaw('
                    SUM(consumo_total) as consumo_total,
                    SUM(CASE WHEN tipo_consumo = \'Tratada\' THEN consumo_total ELSE 0 END) as agua_tratada,
                    SUM(CASE WHEN tipo_consumo = \'Reciclada\' THEN consumo_total ELSE 0 END) as agua_reciclada,
                    SUM(CASE WHEN tipo_consumo = \'Potable\' THEN consumo_total ELSE 0 END) as agua_potable
                ')
                ->first();

            // Calcular porcentajes
            $total = $waterData->consumo_total ?: 1; // Evitar división por cero
            $tratada = round(($waterData->agua_tratada / $total) * 100, 2);
            $reciclada = round(($waterData->agua_reciclada / $total) * 100, 2);
            $potable = round(($waterData->agua_potable / $total) * 100, 2);

            return [
                'datasets' => [
                    [
                        'data' => [$tratada, $reciclada, $potable],
                        'backgroundColor' => [
                            'rgb(54, 162, 235)', // Azul para agua tratada
                            'rgb(75, 192, 192)', // Verde azulado para agua reciclada
                            'rgb(153, 102, 255)', // Morado para agua potable
                        ],
                    ],
                ],
                'labels' => ['Agua Tratada', 'Agua Reciclada', 'Agua Potable'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.label + ': ' + context.raw + '%';
                        }",
                    ],
                ],
            ],
            'maintainAspectRatio' => true,
            'responsive' => true,
            'animation' => [
                'duration' => 0
            ],
        ];
    }
}
