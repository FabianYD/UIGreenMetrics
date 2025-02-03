<?php

namespace App\Filament\Widgets;

use App\Models\Facultad;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Carbon;

class ConsumoFacultad extends BarChartWidget
{
    protected static ?string $heading = 'Consumo por Facultad';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '400px';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 9;

    protected function getData(): array
    {
        $estudiantesPorFacultad = [
            'FACAE' => 2470,
            'FCCSS' => 1583,
            'FECYT' => 4966,
            'FICA' => 1684,
            'FICAYA' => 1175,
        ];

        $facultades = Facultad::with(['campus.consumosAgua' => function ($query) {
            $query->whereMonth('CONSENE_FECHAPAGO', Carbon::now()->month);
        }, 'campus.consumosEnergia' => function ($query) {
            $query->whereMonth('CONSENE_FECHAPAGO', Carbon::now()->month);
        }])->get();

        $labels = [];
        $aguaData = [];
        $energiaData = [];

        foreach ($facultades as $facultad) {
            $codigo = $facultad->FACU_CODIGO;
            $estudiantes = $estudiantesPorFacultad[$codigo] ?? 1; // Evitar división por cero

            $aguaTotal = $facultad->campus->consumosAgua->sum('CONSAG_TOTAL');
            $energiaTotal = $facultad->campus->consumosEnergia->sum('CONSENE_TOTAL');

            $labels[] = $facultad->FACU_NOMBRE;
            $aguaData[] = $aguaTotal / $estudiantes;
            $energiaData[] = $energiaTotal / $estudiantes;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Agua por estudiante (m³)',
                    'data' => $aguaData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutCubic'
                    ]
                ],
                
                [
                    'label' => 'Energía por estudiante (kWh)',
                    'data' => $energiaData,
                    'backgroundColor' => '#FFC107', // Amarillo metálico
                    'borderColor' => '#FFA000',
                    'borderWidth' => 2,
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutQuad'
                    ]
                ],

                
            ],
            'labels' => $labels,
        ];
    }
}
