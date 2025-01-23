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
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Consulta para agua usando subconsulta
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

        // Consulta para energía usando subconsulta
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

        // Combinar y ordenar todas las fechas únicas
        $labels = collect();
        $labels = $labels->concat($consumosAgua->pluck('mes'))
            ->concat($consumosEnergia->pluck('mes'))
            ->unique()
            ->sort()
            ->values();

        // Inicializar arrays con ceros
        $aguaData = array_fill(0, count($labels), 0);
        $energiaData = array_fill(0, count($labels), 0);

        // Llenar datos de agua
        foreach ($consumosAgua as $consumo) {
            $index = $labels->search($consumo->mes);
            if ($index !== false) {
                $aguaData[$index] = floatval($consumo->total_agua);
            }
        }

        // Llenar datos de energía
        foreach ($consumosEnergia as $consumo) {
            $index = $labels->search($consumo->mes);
            if ($index !== false) {
                $energiaData[$index] = floatval($consumo->total_energia);
            }
        }

        // Formatear las etiquetas para mostrar mes y año en español
        $labelsFormateadas = $labels->map(function ($fecha) {
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
                    'data' => $aguaData,
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => '#3b82f680',
                    'tension' => 0.3,
                ],
                [
                    'label' => 'Consumo de Energía (kW/h)',
                    'data' => $energiaData,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0b80',
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
                'y' => [
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
