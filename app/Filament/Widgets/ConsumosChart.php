<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

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

        // Preparar datos de agua, energía y totales
        $data = $allMonths->mapWithKeys(function ($month) use ($consumosAgua, $consumosEnergia) {
            $agua = $consumosAgua->firstWhere('mes', $month)->total_agua ?? 0;
            $energia = $consumosEnergia->firstWhere('mes', $month)->total_energia ?? 0;
            $total = $agua + $energia;

            return [$month => [
                'agua' => $agua,
                'energia' => $energia,
                'total' => $total,
                'agua_percent' => $total > 0 ? round(($agua / $total) * 100, 2) : 0,
                'energia_percent' => $total > 0 ? round(($energia / $total) * 100, 2) : 0,
            ]];
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
                    'data' => $data->pluck('agua')->toArray(),
                    'borderColor' => '#1e40af',
                    'backgroundColor' => '#1e40af80',
                    'pointBackgroundColor' => '#1e40af',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Consumo de Energía (kW/h)',
                    'data' => $data->pluck('energia')->toArray(),
                    'borderColor' => '#ffd700',
                    'backgroundColor' => '#ffd70080',
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
                            const datasetLabel = context.dataset.label || "";
                            const value = context.raw;
                            const dataIndex = context.dataIndex;
                            const agua = context.chart.data.datasets[0].data[dataIndex];
                            const energia = context.chart.data.datasets[1].data[dataIndex];
                            const total = agua + energia;
                            const aguaPercent = ((agua / total) * 100).toFixed(2);
                            const energiaPercent = ((energia / total) * 100).toFixed(2);
                            
                            return `${datasetLabel}: ${value.toLocaleString("es-ES")} (Total: ${total.toLocaleString("es-ES")}) 
                                    \nAgua: ${agua.toLocaleString("es-ES")} (${aguaPercent}%) 
                                    \nEnergía: ${energia.toLocaleString("es-ES")} (${energiaPercent}%)`;
                        }',
                    ],
                ],
            ],
        ];
    }
}
