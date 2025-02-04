<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class GreenMetricRankingStats extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        // Calcular porcentaje de agua tratada
        $aguaConsumida = DB::table('GM_WEC_CONSUMO_AGUA')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('CONSAG_TOTAL');

        $aguaTratada = DB::table('GM_WEC_TRATAMIENTOS_AGUAS')
            ->join('GM_WEC_CONSUMO_AGUA', 'GM_WEC_TRATAMIENTOS_AGUAS.CONSAG_ID', '=', 'GM_WEC_CONSUMO_AGUA.CONSAG_ID')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('TRAGUA_TOTAL');

        $porcentajeTratada = $aguaConsumida > 0 ? ($aguaTratada / $aguaConsumida) * 100 : 0;

        // Calcular porcentaje de agua reutilizada
        $aguaReutilizada = DB::table('GM_WEC_REUTILIZACION_AGUAS')
            ->join('GM_WEC_CONSUMO_AGUA', 'GM_WEC_REUTILIZACION_AGUAS.CONSAG_ID', '=', 'GM_WEC_CONSUMO_AGUA.CONSAG_ID')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('REUAG_CANTIDAD');

        $porcentajeReutilizada = $aguaConsumida > 0 ? ($aguaReutilizada / $aguaConsumida) * 100 : 0;

        // Calcular porcentaje de energía renovable
        $energiaConsumida = DB::table('GM_WEC_CONSUMO_ENERGIAS')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('CONSENE_TOTAL');

        $energiaRenovable = DB::table('GM_WEC_GENERACION_ENERGIAS')
            ->whereYear('GENENE_FECHA', date('Y'))
            ->sum('GENENE_TOTAL');

        $porcentajeRenovable = $energiaConsumida > 0 ? ($energiaRenovable / $energiaConsumida) * 100 : 0;

        return [
            Stat::make('Ranking Agua - Tratamiento', number_format($porcentajeTratada, 1) . '%')
                ->description('Del agua consumida')
                ->descriptionIcon('heroicon-m-beaker')
                ->chart([7, 2, 10, 3, 15, 4, $porcentajeTratada])
                ->color($porcentajeTratada >= 75 ? 'success' : ($porcentajeTratada >= 50 ? 'warning' : 'danger')),

            Stat::make('Ranking Agua - Reutilización', number_format($porcentajeReutilizada, 1) . '%')
                ->description('Del agua consumida')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->chart([7, 2, 10, 3, 15, 4, $porcentajeReutilizada])
                ->color($porcentajeReutilizada >= 75 ? 'success' : ($porcentajeReutilizada >= 50 ? 'warning' : 'danger')),

            Stat::make('Ranking Energía Renovable', number_format($porcentajeRenovable, 1) . '%')
                ->description('De la energía consumida')
                ->descriptionIcon('heroicon-m-bolt')
                ->chart([7, 2, 10, 3, 15, 4, $porcentajeRenovable])
                ->color($porcentajeRenovable >= 75 ? 'success' : ($porcentajeRenovable >= 50 ? 'warning' : 'danger')),
        ];
    }
}
