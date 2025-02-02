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
        $totalConsumo = ConsumoAgua::sum('CONSAG_TOTAL') ?: 1;
        $aguaTratada = ConsumoAgua::where('CONSAG_ES_TRATADA', true)->sum('CONSAG_TOTAL') ?: 0;
        
        return ($aguaTratada / $totalConsumo) * 100;
    }

    public function getPollutionControlStatus()
    {
        $control = ControlContaminacion::orderBy('CONTAM_FECHAINICIO', 'desc')->first();
        
        return $control ? $control->CONTAM_ESTADO : 'sin_implementar';
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
