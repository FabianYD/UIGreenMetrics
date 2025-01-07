<?php

namespace App\Filament\Widgets;

use App\Models\Energy;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class EnergySustainabilityChart extends ChartWidget
{
    protected static ?string $heading = 'Balance Energético';
    protected static ?int $sort = 3;
    protected static ?string $pollingInterval = '300s';

    protected function getData(): array
    {
        return Cache::remember('energy_sustainability_data', 300, function() {
            $currentYear = now()->year;
            $previousYear = now()->subYear()->year;

            // Datos del año actual
            $currentYearData = Energy::whereYear('fecha_registro', $currentYear)
                ->selectRaw('
                    SUM(CASE WHEN tipo_energia = \'Generada\' THEN consumo ELSE 0 END) as energia_generada,
                    SUM(CASE WHEN tipo_energia = \'Consumida\' THEN consumo ELSE 0 END) as energia_consumida
                ')
                ->first();

            // Datos del año anterior para comparación
            $previousYearData = Energy::whereYear('fecha_registro', $previousYear)
                ->selectRaw('
                    SUM(CASE WHEN tipo_energia = \'Consumida\' THEN consumo ELSE 0 END) as energia_consumida
                ')
                ->first();

            // Calcular ahorro/incremento
            $consumoAnterior = $previousYearData->energia_consumida ?: 0;
            $consumoActual = $currentYearData->energia_consumida ?: 0;
            $ahorro = $consumoAnterior > 0 ? 
                $consumoAnterior - $consumoActual : 0;

            return [
                'datasets' => [
                    [
                        'label' => 'Balance Energético (kWh)',
                        'data' => [
                            $currentYearData->energia_generada ?: 0,
                            $consumoActual,
                            $ahorro > 0 ? $ahorro : 0
                        ],
                        'backgroundColor' => [
                            'rgb(75, 192, 192)', // Verde azulado para generada
                            'rgb(255, 99, 132)', // Rojo para consumida
                            'rgb(255, 205, 86)', // Amarillo para ahorro
                        ],
                    ],
                ],
                'labels' => ['Energía Generada', 'Energía Consumida', 'Ahorro vs Año Anterior'],
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.raw + ' kWh';
                        }",
                    ],
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Consumo (kWh)'
                    ]
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
