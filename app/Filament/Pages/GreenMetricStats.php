<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\StatsGreenMetricOverview;
use App\Filament\Widgets\ConsumosChart;
use App\Filament\Widgets\EnergiaRenovableChart;
use Filament\Pages\Page;

class GreenMetricStats extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationLabel = 'Estadísticas Green Metric';
    protected static ?string $title = 'Estadísticas Green Metric';
    protected static ?int $navigationSort = -1;

    protected static string $view = 'filament.pages.green-metric-stats';

    protected function getHeaderWidgets(): array
    {
        return [
            StatsGreenMetricOverview::class,
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ConsumosChart::class,
            EnergiaRenovableChart::class,
        ];
    }
}
