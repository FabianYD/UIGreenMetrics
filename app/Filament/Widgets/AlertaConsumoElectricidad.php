<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;
use Filament\Notifications\Notification;

class AlertaConsumoElectricidad extends ChartWidget
{
    protected static ?string $heading = 'Control Umbral Consumo Electricidad';
    protected static ?string $pollingInterval = '15s';
    protected static ?string $maxHeight = '900px';
    protected static ?int $sort = 8;

    protected function getData(): array
    {
        // Consulta de datos de consumo de electricidad
        $consumosElectricidad = DB::table('GM_WEC_CONSUMO_ENERGIAS')
            ->select(
                DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                DB::raw('SUM(CONSENE_TOTAL) as total_energia'),
                DB::raw('MAX(CONSENE_FECHAPAGO) as ultima_fecha')
            )
            ->groupBy(DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m")'))
            ->orderBy('mes')
            ->get();

        // Generar meses desde el inicio del año
        $allMonths = collect(range(0, 11))->map(function ($i) {
            return now()->startOfYear()->addMonths($i)->format('Y-m');
        });

        $umbral = 6000; // Umbral ajustado para electricidad
        $dataBlue = [];
        $dataRed = [];
        $ultimoConsumo = 0;
        $ultimaFechaConsumo = null;

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
            $registro = $consumosElectricidad->firstWhere('mes', $month);
            $energia = $registro->total_energia ?? 0;
            $fecha = $registro->ultima_fecha ?? null;

            if ($energia > 0) { // Si hay consumo, se guarda como el último registrado
                $ultimoConsumo = $energia;
                $ultimaFechaConsumo = $fecha;
            }

            if ($energia > $umbral) {
                $dataRed[] = $energia - $umbral;
                $dataBlue[] = $umbral;
            } else {
                $dataBlue[] = $energia;
                $dataRed[] = 0;
            }
        }

        // Enviar notificación si el consumo supera el umbral
        $this->calcularConsumoEnergia($ultimoConsumo, $umbral, $ultimaFechaConsumo);

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

    public function calcularConsumoEnergia($consumo, $umbral, $fecha): void
    {
        $fechaFormateada = $fecha ? date('d/m/Y', strtotime($fecha)) : 'Fecha desconocida';
        $esExceso = $consumo > $umbral;

        Notification::make()
            ->title($esExceso ? '⚡ ¡Alerta de Consumo de Electricidad!' : '✅ Consumo dentro del límite')
            ->body("Fecha: $fechaFormateada \nTotal consumido: $consumo kWh. \n" . 
                   ($esExceso ? "Se ha superado el umbral de $umbral kWh." : "Está dentro del umbral de $umbral kWh."))
            ->{$esExceso ? 'danger' : 'success'}()
            #->duration(10) // La notificación desaparecerá después de 10 segundos
            ->send();
    }
}
