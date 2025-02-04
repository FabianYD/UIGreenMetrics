<?php

namespace App\Filament\Widgets;

use App\Models\Campus;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Carbon;

class ConsumoPorCampusChart extends BarChartWidget
{
    protected static ?string $heading = 'Consumo por Campus';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '400px';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $campus = Campus::withSum(['consumosAgua as agua_total' => function($query) {
            $query->whereMonth('CONSENE_FECHAPAGO', Carbon::now()->month);
        }], 'CONSAG_TOTAL')
        ->withSum(['consumosEnergia as energia_total' => function($query) {
            $query->whereMonth('CONSENE_FECHAPAGO', Carbon::now()->month);
        }], 'CONSENE_TOTAL')
        ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Agua (m³)',
                    'data' => $campus->pluck('agua_total'),
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutCubic'
                    ]
                ],
                [
                    'label' => 'Energía (kWh)',
                    'data' => $campus->pluck('energia_total'),
                    'backgroundColor' => '#FFC107',
                    'borderColor' => '#FFA000',
                    'borderWidth' => 2,
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutQuad'
                    ]
                ],

                

            ],
            'labels' => $campus->pluck('CAMPUS_NOMBRES'),
        ];
    }

    
}