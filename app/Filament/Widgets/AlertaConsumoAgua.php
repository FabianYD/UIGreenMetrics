<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;

class AlertaConsumoAgua extends ChartWidget
{
    protected static ?string $heading = 'Control Humbral Consumo Agua';
    protected static ?string $pollingInterval = '15s';
    protected static ?string $maxHeight = '900px';
    protected static ?int $sort = 6;

    protected function getData(): array
    {
        $consumosAgua = DB::table('GM_WEC_CONSUMO_AGUA')
            ->select(
                DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                DB::raw('SUM(CONSAG_TOTAL) as total_agua')
            )
            ->groupBy(DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m")'))
            ->orderBy('mes')
            ->get();

        $allMonths = collect(range(0, 11))->map(function ($i) {
            return now()->startOfYear()->addMonths($i)->format('Y-m');
        });

        $umbral = 6000;
        $dataBlue = [];
        $dataRed = [];
        $labelsFormateadas = $allMonths->map(function ($fecha) {
            $meses = [
                '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
                '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
                '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
            ];
            list($anio, $mes) = explode('-', $fecha);
            return $meses[$mes] . ' ' . $anio;
        });

        foreach ($allMonths as $month) {
            $agua = $consumosAgua->firstWhere('mes', $month)->total_agua ?? 0;

            if ($agua > $umbral) {
                $dataRed[] = $agua - $umbral;
                $dataBlue[] = $umbral;
            } else {
                $dataBlue[] = $agua;
                $dataRed[] = 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Consumo dentro del umbral',
                    'data' => $dataBlue,
                    'backgroundColor' => '#1e40af',
                    'borderColor' => '#1e40af',
                    'borderWidth' => 2,
                ],
                [
                    'label' => 'Consumo sobre el umbral',
                    'data' => $dataRed,
                    'backgroundColor' => '#FF0000',
                    'borderColor' => '#FF0000',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labelsFormateadas->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
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
                        'text' => 'Consumo de Agua',
                        'font' => ['size' => 14],
                    ],
                    'beginAtZero' => true,
                ],
            ],
            'elements' => [
                'bar' => [
                    'borderRadius' => 5,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'enabled' => true,
                    'callbacks' => [
                        'label' => 'function(context) {
                            const datasetLabel = context.dataset.label || "";
                            const value = context.raw;
                            return `${datasetLabel}: ${value.toLocaleString("es-ES")}`;
                        }',
                    ],
                ],
            ],
        ];
    }
}
