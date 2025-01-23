<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoEnergia;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class EnergiaBarChart extends ChartWidget
{
    protected static ?string $heading = 'Consumo de Energía por Mes';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $data = ConsumoEnergia::select(
            DB::raw('MONTH(CONSENE_FECHAPAGO) as mes'),
            DB::raw('SUM(CONSENE_TOTAL) as total_energia')
        )
        ->whereYear('CONSENE_FECHAPAGO', date('Y'))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        $labels = [];
        $values = [];

        foreach ($data as $record) {
            $labels[] = $this->getNombreMes($record->mes);
            $values[] = $record->total_energia;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Consumo de Energía (kWh)',
                    'data' => $values,
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#E91E63',
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
