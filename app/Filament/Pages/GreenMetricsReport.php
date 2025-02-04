<?php

namespace App\Filament\Pages;

use App\Models\AguaReciclada;
use App\Models\ConsumoAgua;
use App\Models\ControlContaminacion;
use App\Models\DispositivoEficiente;
use App\Models\ProgramaConservacion;
use App\Models\TratamientoAgua;
use Filament\Pages\Page;

class GreenMetricsReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Reporte GreenMetrics';
    protected static ?string $navigationGroup = 'Reportes';
    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.green-metrics-report';

    public function getWaterConservationProgress()
    {
        $programas = ProgramaConservacion::all();
        $programasActivos = $programas->whereIn('PROGCONS_ESTADO', ['implementacion', 'evaluacion']);
        
        return [
            'total_programas' => $programas->count(),
            'programas_activos' => $programasActivos->count(),
            'avance_promedio' => $programasActivos->avg('PROGCONS_AVANCE') ?? 0,
            'puntaje' => $this->calcularPuntajeConservacion($programasActivos->count())
        ];
    }

    private function calcularPuntajeConservacion($totalActivos)
    {
        return match(true) {
            $totalActivos >= 4 => 300,  // Más de 4 programas
            $totalActivos >= 2 => 200,  // 2-3 programas
            $totalActivos >= 1 => 100,  // 1 programa
            default => 0                 // Ningún programa
        };
    }

    public function getWaterRecyclingPercentage()
    {
        $totalConsumo = ConsumoAgua::sum('CONSAG_TOTAL') ?: 1;
        $totalReciclado = AguaReciclada::sum('AGUAREC_CANTIDAD') ?: 0;
        $porcentaje = ($totalReciclado / $totalConsumo) * 100;
        
        return [
            'porcentaje' => $porcentaje,
            'total_reciclado' => $totalReciclado,
            'total_consumo' => $totalConsumo,
            'puntaje' => $this->calcularPuntajeReciclaje($porcentaje)
        ];
    }

    private function calcularPuntajeReciclaje($porcentaje)
    {
        return match(true) {
            $porcentaje >= 50 => 300,   // >50% reciclado
            $porcentaje >= 25 => 200,   // 25-50% reciclado
            $porcentaje >= 10 => 100,   // 10-25% reciclado
            default => 0                 // <10% reciclado
        };
    }

    public function getEfficientAppliancesPercentage()
    {
        $dispositivos = DispositivoEficiente::all();
        $total = $dispositivos->count() ?: 1;
        $eficientes = $dispositivos->where('DISPEF_EFICIENCIA', '>', 60)->count();
        $porcentaje = ($eficientes / $total) * 100;
        
        return [
            'porcentaje' => $porcentaje,
            'total_dispositivos' => $total,
            'dispositivos_eficientes' => $eficientes,
            'puntaje' => $this->calcularPuntajeDispositivos($porcentaje)
        ];
    }

    private function calcularPuntajeDispositivos($porcentaje)
    {
        return match(true) {
            $porcentaje >= 75 => 300,   // >75% eficientes
            $porcentaje >= 50 => 200,   // 50-75% eficientes
            $porcentaje >= 25 => 100,   // 25-50% eficientes
            default => 0                 // <25% eficientes
        };
    }

    public function getTreatedWaterPercentage()
    {
        $totalTratado = TratamientoAgua::sum('TRAGUA_TOTAL') ?: 0;
        $totalConsumo = ConsumoAgua::sum('CONSAG_TOTAL') ?: 1;
        $porcentaje = ($totalTratado / $totalConsumo) * 100;
        
        return [
            'porcentaje' => $porcentaje,
            'total_tratado' => $totalTratado,
            'total_consumo' => $totalConsumo,
            'puntaje' => $this->calcularPuntajeTratamiento($porcentaje)
        ];
    }

    private function calcularPuntajeTratamiento($porcentaje)
    {
        return match(true) {
            $porcentaje >= 75 => 300,   // >75% tratado
            $porcentaje >= 50 => 200,   // 50-75% tratado
            $porcentaje >= 25 => 100,   // 25-50% tratado
            default => 0                 // <25% tratado
        };
    }

    public function getPollutionControlStatus()
    {
        $control = ControlContaminacion::orderBy('CONTAM_FECHAINICIO', 'desc')->first();
        $estado = 'sin_implementar';
        
        if ($control) {
            $estado = match ($control->CONTAM_ESTADO) {
                'planificacion' => 'planificacion',
                'implementacion_temprana' => 'implementacion_temprana',
                'implementacion_completa' => 'implementacion_completa',
                default => 'sin_implementar',
            };
        }

        return [
            'estado' => $estado,
            'fecha_inicio' => $control?->CONTAM_FECHAINICIO,
            'puntaje' => $this->calcularPuntajeContaminacion($estado)
        ];
    }

    private function calcularPuntajeContaminacion($estado)
    {
        return match($estado) {
            'implementacion_completa' => 300,
            'implementacion_temprana' => 200,
            'planificacion' => 100,
            default => 0
        };
    }

    public function getWaterUsageReduction()
    {
        $consumoActual = ConsumoAgua::whereYear('CONSENE_FECHAPAGO', date('Y'))->sum('CONSAG_TOTAL');
        $consumoAnterior = ConsumoAgua::whereYear('CONSENE_FECHAPAGO', date('Y')-1)->sum('CONSAG_TOTAL');
        
        $reduccion = 0;
        if ($consumoAnterior > 0) {
            $reduccion = (($consumoAnterior - $consumoActual) / $consumoAnterior) * 100;
        }

        return [
            'reduccion' => $reduccion,
            'consumo_actual' => $consumoActual,
            'consumo_anterior' => $consumoAnterior,
            'puntaje' => $this->calcularPuntajeReduccion($reduccion)
        ];
    }

    private function calcularPuntajeReduccion($reduccion)
    {
        return match(true) {
            $reduccion >= 20 => 300,    // >20% reducción
            $reduccion >= 10 => 200,    // 10-20% reducción
            $reduccion >= 5 => 100,     // 5-10% reducción
            default => 0                 // <5% reducción
        };
    }

    protected function getViewData(): array
    {
        $data = [
            'waterConservation' => $this->getWaterConservationProgress(),
            'waterRecycling' => $this->getWaterRecyclingPercentage(),
            'efficientAppliances' => $this->getEfficientAppliancesPercentage(),
            'treatedWater' => $this->getTreatedWaterPercentage(),
            'pollutionControl' => $this->getPollutionControlStatus(),
            'waterUsageReduction' => $this->getWaterUsageReduction(),
        ];

        // Calcular puntaje total de la variable agua
        $data['totalScore'] = 
            $data['waterConservation']['puntaje'] +
            $data['waterRecycling']['puntaje'] +
            $data['efficientAppliances']['puntaje'] +
            $data['treatedWater']['puntaje'] +
            $data['pollutionControl']['puntaje'] +
            $data['waterUsageReduction']['puntaje'];

        return $data;
    }
}
