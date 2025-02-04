<?php

namespace App\Filament\Widgets;

use App\Models\ConsumoAgua;
use App\Models\ConsumoEnergia;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsGreenMetricOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Agua Consumida Total
        $aguaConsumida = ConsumoAgua::select(DB::raw('SUM(CONSAG_TOTAL) as total_agua'))
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->first();

        // Agua Tratada
        $aguaTratada = DB::table('GM_WEC_TRATAMIENTOS_AGUAS')
            ->join('GM_WEC_CONSUMO_AGUA', 'GM_WEC_TRATAMIENTOS_AGUAS.CONSAG_ID', '=', 'GM_WEC_CONSUMO_AGUA.CONSAG_ID')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('TRAGUA_TOTAL');

        // Agua Reutilizada
        $aguaReutilizada = DB::table('GM_WEC_REUTILIZACION_AGUAS')
            ->join('GM_WEC_CONSUMO_AGUA', 'GM_WEC_REUTILIZACION_AGUAS.CONSAG_ID', '=', 'GM_WEC_CONSUMO_AGUA.CONSAG_ID')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('REUAG_CANTIDAD');

        // Energía Consumida Total
        $energiaConsumida = ConsumoEnergia::select(DB::raw('SUM(CONSENE_TOTAL) as total_energia'))
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->first();

        // Energía Renovable
        $energiaRenovable = DB::table('GM_WEC_GENERACION_ENERGIAS')
            ->whereYear('GENENE_FECHA', date('Y'))
            ->sum('GENENE_TOTAL');

        // Costos
        $costoAgua = DB::table('GM_WEC_COSTOS_AGUA')
            ->join('GM_WEC_CONSUMO_AGUA', 'GM_WEC_COSTOS_AGUA.CONSAG_ID', '=', 'GM_WEC_CONSUMO_AGUA.CONSAG_ID')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('COSTOAG_TOTAL');

        $costoEnergia = DB::table('GM_WEC_COSTOS_ENERGIAS')
            ->join('GM_WEC_CONSUMO_ENERGIAS', 'GM_WEC_COSTOS_ENERGIAS.CONSENE_ID', '=', 'GM_WEC_CONSUMO_ENERGIAS.CONSENE_ID')
            ->whereYear('CONSENE_FECHAPAGO', date('Y'))
            ->sum('COSTENE_TOTAL');

        // Cálculo de porcentajes para las descripciones
        $porcentajeRenovable = 0;
        if ($energiaConsumida->total_energia > 0) {
            $porcentajeRenovable = ($energiaRenovable / $energiaConsumida->total_energia) * 100;
        }

        return [
            // Primera fila - Agua
            Stat::make('Agua Consumida Total', number_format($aguaConsumida->total_agua ?? 0, 2) . ' m³')
                ->description('Total del agua consumida')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Agua Tratada', number_format($aguaTratada ?? 0, 2) . ' m³')
                ->description('Total del agua tratada')
                ->descriptionIcon('heroicon-m-beaker')
                ->color('success'),

            Stat::make('Agua Reutilizada', number_format($aguaReutilizada ?? 0, 2) . ' m³')
                ->description('Total del agua reutilizada')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->color('success'),

            // Segunda fila - Energía y Costos
            Stat::make('Energía Consumida Total', number_format($energiaConsumida->total_energia ?? 0, 2) . ' kWh')
                ->description(number_format($porcentajeRenovable, 1) . '% del consumo total')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('warning'),

            // Costos
            Stat::make('Costo Total Agua', '$ ' . number_format($costoAgua ?? 0, 2))
                ->description('Inversión total en agua')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),

            Stat::make('Costo Total Energía', '$ ' . number_format($costoEnergia ?? 0, 2))
                ->description('Inversión total en energía')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('danger'),
        ];
    }
}
