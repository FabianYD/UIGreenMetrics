<?php

namespace App\Filament\Widgets;

use Illuminate\Support\Facades\DB;
use Filament\Widgets\ChartWidget;
use Filament\Notifications\Notification;

class AlertaConsumoAgua extends ChartWidget
{
    protected static ?string $heading = 'Control Umbral Consumo Agua';
    protected static ?string $pollingInterval = '15s';
    protected static ?string $maxHeight = '900px';
    protected static ?int $sort = 7;

    protected function getData(): array
    {
        $consumosAgua = DB::table('GM_WEC_CONSUMO_AGUA')
            ->select(
                DB::raw('DATE_FORMAT(CONSENE_FECHAPAGO, "%Y-%m") as mes'),
                DB::raw('SUM(CONSAG_TOTAL) as total_agua'),
                DB::raw('MAX(CONSENE_FECHAPAGO) as ultima_fecha') // Guardamos la última fecha de pago
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
        $labelsFormateadas = [];
        $ultimoConsumo = 0;
        $ultimaFechaConsumo = null;

        foreach ($allMonths as $month) {
            $registro = $consumosAgua->firstWhere('mes', $month);
            $agua = $registro->total_agua ?? 0;
            $fecha = $registro->ultima_fecha ?? null;

            if ($agua > 0) { // Si hay consumo, se guarda como el último registrado
                $ultimoConsumo = $agua;
                $ultimaFechaConsumo = $fecha;
            }

            if ($agua > $umbral) {
                $dataRed[] = $agua - $umbral;
                $dataBlue[] = $umbral;
            } else {
                $dataBlue[] = $agua;
                $dataRed[] = 0;
            }

            // Formatear etiquetas de meses
            $meses = [
                '01' => 'Ene', '02' => 'Feb', '03' => 'Mar', '04' => 'Abr',
                '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Ago',
                '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dic'
            ];
            list($anio, $mes) = explode('-', $month);
            $labelsFormateadas[] = $meses[$mes] . ' ' . $anio;
        }

        // Evaluar consumo del último mes registrado con fecha
        $this->calcularConsumoAgua($ultimoConsumo, $umbral, $ultimaFechaConsumo);

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
            'labels' => $labelsFormateadas,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public function calcularConsumoAgua($consumo, $umbral, $fecha): void
    {
        $fechaFormateada = $fecha ? date('d/m/Y', strtotime($fecha)) : 'Fecha desconocida';

        Notification::make()
            ->title($consumo > $umbral ? '⚠️ ¡Alerta de Consumo de Agua!' : '🎉 Consumo dentro del límite')
            ->body("Fecha: $fechaFormateada \nTotal consumido: $consumo litros. \n" . 
                   ($consumo > $umbral ? "Se ha superado el umbral de $umbral litros." : "Está dentro del umbral de $umbral litros."))
            ->{$consumo > $umbral ? 'danger' : 'success'}()
            //->duration(30) // La notificación desaparecerá después de 10 segundos
            ->send();
    }
}
