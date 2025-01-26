<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoAgua;
use App\Models\ConsumoEnergia;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ConsumosChart extends ChartWidget
{
    protected static ?string $heading = 'Consumos Mensuales';
    protected static ?string $pollingInterval = '15s';
    protected static ?string $maxHeight = '900px';

    protected function getData(): array
    {
        // Consulta para agua
        $consumosAgua = DB::table(function ($query) {
            $query->from('GM_WEC_CONSUMO_AGUA')
                ->select(
                    DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                    DB::raw('SUM(CONSAG_TOTAL) as total_agua')
                )
                ->groupBy(DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m")'));
        }, 'agua_mensual')
            ->orderBy('mes')
            ->get();

        // Consulta para energía
        $consumosEnergia = DB::table(function ($query) {
            $query->from('GM_WEC_CONSUMO_ENERGIAS')
                ->select(
                    DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                    DB::raw('SUM(CONSENE_TOTAL) as total_energia')
                )
                ->groupBy(DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m")'));
        }, 'energia_mensual')
            ->orderBy('mes')
            ->get();

        // Combinar y generar todos los meses dentro del rango
        $allMonths = collect(range(0, 11))->map(function ($i) {
            return now()->startOfYear()->addMonths($i)->format('Y-m');
        });

        // Preparar datos de agua y energía
        $aguaData = $allMonths->map(function ($month) use ($consumosAgua) {
            return $consumosAgua->firstWhere('mes', $month)->total_agua ?? 0;
        });
        $energiaData = $allMonths->map(function ($month) use ($consumosEnergia) {
            return $consumosEnergia->firstWhere('mes', $month)->total_energia ?? 0;
        });

        // Formatear etiquetas del eje X (meses)
        $labelsFormateadas = $allMonths->map(function ($fecha) {
            $meses = [
                '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
                '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
                '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
            ];
            list($anio, $mes) = explode('-', $fecha);
            return $meses[$mes] . ' ' . $anio;
        });

        return [
            'datasets' => [
                [
                    'label' => 'Consumo de Agua (m³)',
                    'data' => $aguaData->toArray(),
                    'borderColor' => '#1e40af', // Azul oscuro
                    'backgroundColor' => '#1e40af80', // Azul semitransparente
                    'pointBackgroundColor' => '#1e40af',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Consumo de Energía (kW/h)',
                    'data' => $energiaData->toArray(),
                    'borderColor' => '#ffd700', // Amarillo metálico
                    'backgroundColor' => '#ffd70080', // Amarillo semitransparente
                    'pointBackgroundColor' => '#ffd700',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labelsFormateadas->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Meses',
                        'font' => ['size' => 14],
                    ],
                ],
                'y' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Consumo',
                        'font' => ['size' => 14],
                    ],
                    'beginAtZero' => true,
                    'ticks' => [
                        'callback' => 'function(value) { return value.toLocaleString("es-ES") }',
                    ],
                ],
            ],
            'elements' => [
                'point' => [
                    'radius' => 4,
                    'hoverRadius' => 6,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            let value = context.parsed.y;
                            return label + ": " + value.toLocaleString("es-ES");
                        }',
                    ],
                ],
            ],
        ];
    }
}

