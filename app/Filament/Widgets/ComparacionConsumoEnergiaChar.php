<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoEnergia;
use Filament\Widgets\ChartWidget;

class ComparacionConsumoEnergiaChar extends ChartWidget
{
    protected static ?string $heading = 'Comparación de Consumo de Electricidad';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        // Consumo de electricidad
        $energiaActual = ConsumoEnergia::whereYear('CONSENE_FECHAPAGO', $currentYear)->sum('CONSENE_TOTAL');
        $energiaAnterior = ConsumoEnergia::whereYear('CONSENE_FECHAPAGO', $previousYear)->sum('CONSENE_TOTAL');

        // Diferencia de consumo
        $energiaDiferencia = $energiaActual - $energiaAnterior;

        return [
            'datasets' => [
                [
                    'label' => 'Consumo Año Anterior (kWh)',
                    'data' => [$energiaAnterior],
                    'backgroundColor' => '#FFD700', // Amarillo normal
                    'borderColor' => '#FFC300',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Consumo Año Actual (kWh)',
                    'data' => [$energiaActual],
                    'backgroundColor' => '#FFC107', // Amarillo metálico
                    'borderColor' => '#FFA000',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Diferencia de Consumo (kWh)',
                    'data' => [$energiaDiferencia],
                    'backgroundColor' => '#4CAF50', // Verde
                    'borderColor' => '#388E3C',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Electricidad'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
