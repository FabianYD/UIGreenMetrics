<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoAgua;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AguaBarChart extends ChartWidget
{
    protected static ?string $heading = 'Consumo de Agua por Mes';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = ConsumoAgua::select(
            DB::raw('MONTH(CONSENE_FECHAPAGO) as mes'),
            DB::raw('SUM(CONSAG_TOTAL) as total_agua')
        )
        ->whereYear('CONSENE_FECHAPAGO', date('Y'))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        $labels = [];
        $values = [];

        foreach ($data as $record) {
            $labels[] = $this->getNombreMes($record->mes);
            $values[] = $record->total_agua;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Consumo de Agua (m³)',
                    'data' => $values,
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#2196F3',
                    'borderWidth' => 2,
                ]
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];
        return $meses[$mes] ?? '';
    }
}
