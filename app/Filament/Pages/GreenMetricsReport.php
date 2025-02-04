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
        $programas = ProgramaConservacion::where('PROGCONS_ESTADO', 'implementacion')
            ->orWhere('PROGCONS_ESTADO', 'evaluacion')
            ->get();
        
        return [
            'total' => $programas->count(),
            'avance_promedio' => $programas->avg('PROGCONS_AVANCE') ?? 0,
        ];
    }

    public function getWaterRecyclingPercentage()
    {
        $totalConsumo = ConsumoAgua::sum('CONSAG_TOTAL') ?: 1;
        $totalReciclado = AguaReciclada::sum('AGUAREC_CANTIDAD') ?: 0;
        
        return ($totalReciclado / $totalConsumo) * 100;
    }

    public function getEfficientAppliancesPercentage()
    {
        $dispositivos = DispositivoEficiente::all();
        $total = $dispositivos->count() ?: 1;
        $eficientes = $dispositivos->where('DISPEF_EFICIENCIA', '>', 60)->count();
        
        return ($eficientes / $total) * 100;
    }

    public function getTreatedWaterPercentage()
    {
        $totalTratado = TratamientoAgua::sum('TRAGUA_TOTAL') ?: 0;
        $totalConsumo = ConsumoAgua::sum('CONSAG_TOTAL') ?: 1;
        
        return ($totalTratado / $totalConsumo) * 100;
    }

    public function getPollutionControlStatus()
    {
        $control = ControlContaminacion::orderBy('CONTAM_FECHAINICIO', 'desc')->first();
        
        if (!$control) {
            return 'sin_implementar';
        }

        return match ($control->CONTAM_ESTADO) {
            'planificacion' => 'planificacion',
            'implementacion_temprana' => 'implementacion_temprana',
            'implementacion_completa' => 'implementacion_completa',
            default => 'sin_implementar',
        };
    }

    protected function getViewData(): array
    {
        return [
            'waterConservation' => $this->getWaterConservationProgress(),
            'waterRecycling' => $this->getWaterRecyclingPercentage(),
            'efficientAppliances' => $this->getEfficientAppliancesPercentage(),
            'treatedWater' => $this->getTreatedWaterPercentage(),
            'pollutionControl' => $this->getPollutionControlStatus(),
        ];
    }
}
