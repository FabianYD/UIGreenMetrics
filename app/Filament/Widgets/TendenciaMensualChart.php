<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoAgua;
use App\Models\ConsumoEnergia;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TendenciaMensualChart extends LineChartWidget
{
    protected static ?string $heading = 'Tendencia Mensual de Consumo';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '400px';
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $meses = collect();
        $consumosAgua = collect();
        $consumosEnergia = collect();

        // Obtener datos de los últimos 12 meses
        for ($i = 11; $i >= 0; $i--) {
            $fecha = Carbon::now()->subMonths($i);
            $meses->push($fecha->format('M Y'));

            // Sumar consumos de agua del mes
            $aguaMes = ConsumoAgua::whereYear('CONSENE_FECHAPAGO', $fecha->year)
                ->whereMonth('CONSENE_FECHAPAGO', $fecha->month)
                ->sum('CONSAG_TOTAL');
            $consumosAgua->push($aguaMes ?? 0);

            // Sumar consumos de energía del mes
            $energiaMes = ConsumoEnergia::whereYear('CONSENE_FECHAPAGO', $fecha->year)
                ->whereMonth('CONSENE_FECHAPAGO', $fecha->month)
                ->sum('CONSENE_TOTAL');
            $consumosEnergia->push($energiaMes ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Agua (m³)',
                    'data' => $consumosAgua,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutExpo'
                    ]
                ],
                [
                    'label' => 'Energía (kWh)',
                    'data' => $consumosEnergia,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.2)',
                    'fill' => true,
                    'tension' => 0.4,
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutExpo'
                    ]
                ],
            ],
            'labels' => $meses->toArray(),
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => "function(value) { return value.toLocaleString('es-ES'); }"
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top'
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += context.parsed.y.toLocaleString('es-ES');
                            return label;
                        }"
                    ]
                ]
            ],
            'interaction' => [
                'intersect' => false,
                'mode' => 'index'
            ],
            'animation' => [
                'duration' => 2000
            ],
            'responsive' => true,
            'maintainAspectRatio' => false
        ];
    }
}