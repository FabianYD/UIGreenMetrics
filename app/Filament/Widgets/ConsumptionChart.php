<?php

namespace App\Filament\Widgets;

use App\Models\Energy;
use App\Models\Water;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ConsumptionChart extends ChartWidget
{
    protected static ?string $heading = 'Consumo Mensual';
    protected static ?int $sort = 1;
    
    // Actualizar cada 5 minutos
    protected static ?string $pollingInterval = '300s';

    // Usar caché para los datos
    protected function getData(): array
    {
        return Cache::remember('consumption_chart_data', 300, function() {
            $energyData = Energy::select(
                DB::raw('EXTRACT(MONTH FROM fecha_registro) as month'),
                DB::raw('SUM(consumo) as total_consumo')
            )
            ->whereYear('fecha_registro', now()->year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM fecha_registro)'))
            ->orderBy('month')
            ->get()
            ->pluck('total_consumo', 'month')
            ->toArray();

            $waterData = Water::select(
                DB::raw('EXTRACT(MONTH FROM fecha_pago) as month'),
                DB::raw('SUM(consumo_total) as total_consumo')
            )
            ->whereYear('fecha_pago', now()->year)
            ->groupBy(DB::raw('EXTRACT(MONTH FROM fecha_pago)'))
            ->orderBy('month')
            ->get()
            ->pluck('total_consumo', 'month')
            ->toArray();

            // Asegurar que tenemos datos para todos los meses
            $allMonths = range(1, 12);
            $formattedEnergyData = array_fill_keys($allMonths, 0);
            $formattedWaterData = array_fill_keys($allMonths, 0);

            foreach ($energyData as $month => $value) {
                $formattedEnergyData[(int)$month] = $value;
            }

            foreach ($waterData as $month => $value) {
                $formattedWaterData[(int)$month] = $value;
            }

            $months = array_map(function($month) {
                return date('M', mktime(0, 0, 0, $month, 1));
            }, $allMonths);

            return [
                'datasets' => [
                    [
                        'label' => 'Consumo de Energía',
                        'data' => array_values($formattedEnergyData),
                        'borderColor' => '#36A2EB',
                        'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                        'fill' => true,
                    ],
                    [
                        'label' => 'Consumo de Agua',
                        'data' => array_values($formattedWaterData),
                        'borderColor' => '#4BC0C0',
                        'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                        'fill' => true,
                    ]
                ],
                'labels' => $months,
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Consumo'
                    ]
                ],
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Mes'
                    ]
                ]
            ],
            'animation' => [
                'duration' => 0 // Desactivar animaciones para mejorar rendimiento
            ],
            'maintainAspectRatio' => true,
            'responsive' => true,
        ];
    }
}
