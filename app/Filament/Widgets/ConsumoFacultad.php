<?php

namespace App\Filament\Widgets;

use App\Models\Facultad;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ConsumoFacultad extends BarChartWidget
{
    protected static ?string $heading = 'Consumo por Facultad';
    protected static ?string $pollingInterval = '10s';
    protected static ?string $maxHeight = '400px';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 9;

    protected function getData(): array
    {
        // Total de estudiantes por facultad
        $estudiantesPorFacultad = [
            'FACAE' => 2470,
            'FCCSS' => 1583,
            'FECYT' => 4966,
            'FICA' => 1684,
            'FICAYA' => 1175,
        ];

        // Obtener el consumo total de agua para el mes actual
        $consumosAgua = DB::table(function ($query) {
            $query->from('GM_WEC_CONSUMO_AGUA')
                ->select(
                    DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                    DB::raw('SUM(CONSAG_TOTAL) as total_agua')
                )
                ->whereMonth('CONSENE_FECHAPAGO', Carbon::now()->month) // Solo el mes actual
                ->groupBy(DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m")'));
        }, 'agua_mensual')
            ->orderBy('mes')
            ->first(); // Obtener solo el primer registro, ya que es para el mes actual

        // Obtener el consumo total de energía para el mes actual
        $consumosEnergia = DB::table(function ($query) {
            $query->from('GM_WEC_CONSUMO_ENERGIAS')
                ->select(
                    DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                    DB::raw('SUM(CONSENE_TOTAL) as total_energia')
                )
                ->whereMonth('CONSENE_FECHAPAGO', Carbon::now()->month) // Solo el mes actual
                ->groupBy(DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m")'));
        }, 'energia_mensual')
            ->orderBy('mes')
            ->first(); // Obtener solo el primer registro, ya que es para el mes actual

        // Si no se encontraron datos para el mes actual, establecer valores predeterminados
        $totalConsumoAgua = $consumosAgua->total_agua ?? 0;
        $totalConsumoEnergia = $consumosEnergia->total_energia ?? 0;

        // Total de estudiantes en todas las facultades
        $totalEstudiantes = array_sum($estudiantesPorFacultad);

        // Labels y arrays para los datos
        $labels = [];
        $aguaData = [];
        $energiaData = [];

        // Distribuir el consumo de agua y energía a cada facultad según su proporción de estudiantes
        foreach ($estudiantesPorFacultad as $codigo => $estudiantes) {
            // Proporción de estudiantes de la facultad
            $proporcionEstudiantes = $estudiantes / $totalEstudiantes;
            
            // Calcular el consumo de agua y energía de acuerdo con la proporción
            $consumoAguaFacultad = $proporcionEstudiantes * $totalConsumoAgua;
            $consumoEnergiaFacultad = $proporcionEstudiantes * $totalConsumoEnergia;

            // Guardar los resultados para mostrar en el gráfico
            $facultad = Facultad::where('FACU_CODIGO', $codigo)->first();
            if (!$facultad) {
                continue;
            }
            
            $labels[] = $facultad->FACU_NOMBRE;
            $aguaData[] = round($consumoAguaFacultad, 2);
            $energiaData[] = round($consumoEnergiaFacultad, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Consumo de Agua por Facultad (m³)',
                    'data' => $aguaData,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.8)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'animation' => [
                        'duration' => 2000,
                        'easing' => 'easeInOutCubic'
                    ]
                ],
                
                [
                    'label' => 'Consumo de Energía por Facultad (kWh)',
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
