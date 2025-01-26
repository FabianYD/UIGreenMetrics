<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoAgua;
use Filament\Widgets\ChartWidget;

class ComparacionConsumoAguaChar extends ChartWidget
{
    protected static ?string $heading = 'Comparación de Consumo de Agua';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $currentYear = date('Y');
        $previousYear = $currentYear - 1;

        // Consumo de Agua
        $aguaActual = ConsumoAgua::whereYear('CONSENE_FECHAPAGO', $currentYear)->sum('CONSAG_TOTAL');
        $aguaAnterior = ConsumoAgua::whereYear('CONSENE_FECHAPAGO', $previousYear)->sum('CONSAG_TOTAL');
        $diferenciaAgua = $aguaActual - $aguaAnterior;

        return [
            'datasets' => [
                [
                    'label' => 'Consumo Año Anterior (m³)',
                    'data' => [$aguaAnterior],
                    'backgroundColor' => '#ADD8E6', // Celeste claro
                    'borderColor' => '#87CEEB', // Azul claro para borde
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Consumo Año Actual (m³)',
                    'data' => [$aguaActual],
                    'backgroundColor' => '#36A2EB', // Azul
                    'borderColor' => '#1E88E5',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Diferencia (m³)',
                    'data' => [$diferenciaAgua],
                    'backgroundColor' => '#4CAF50', // Verde
                    'borderColor' => '#388E3C',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => ['Consumo de Agua'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
