<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoEnergia;
use App\Models\GeneracionEnergias;
use Filament\Widgets\ChartWidget;

class EnergiaRenovableChart extends ChartWidget
{
    protected static ?string $heading = 'Distribución de Energía';
    protected static ?string $pollingInterval = '15s';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $energiaConsumida = ConsumoEnergia::sum('CONSENE_TOTAL');
        $energiaGenerada = GeneracionEnergias::sum('GENENE_TOTAL');
        $energiaNoRenovable = max(0, $energiaConsumida - $energiaGenerada);

        return [
            'datasets' => [
                [
                    'data' => [$energiaGenerada, $energiaNoRenovable],
                    'backgroundColor' => ['#22c55e', '#ef4444'],
                ],
            ],
            'labels' => ['Energía Renovable', 'Energía No Renovable'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
