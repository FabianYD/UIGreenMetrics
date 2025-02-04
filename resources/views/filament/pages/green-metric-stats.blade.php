<x-filament-panels::page>
    <x-filament::section>
        <div class="grid grid-cols-1 gap-y-8">
            <div>
                @livewire(\App\Filament\Widgets\StatsGreenMetricOverview::class)
            </div>

            <div class="prose max-w-none dark:prose-invert">
                <h2>Ranking Green Metric</h2>
                <p>
                    Análisis del desempeño en las variables de agua y energía según los criterios de UI GreenMetric World University Rankings.
                </p>
            </div>

            <div>
                @livewire(\App\Filament\Widgets\GreenMetricRankingStats::class)
            </div>

            <div class="prose max-w-none dark:prose-invert">
                <h2>Gráficos de Consumo</h2>
                <p>
                    Estos gráficos muestran las tendencias de consumo de agua y energía a lo largo del tiempo.
                    Los datos se actualizan automáticamente para reflejar los últimos registros disponibles.
                </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                @livewire(\App\Filament\Widgets\ConsumosChart::class)
                @livewire(\App\Filament\Widgets\EnergiaRenovableChart::class)
            </div>
        </div>
    </x-filament::section>
</x-filament-panels::page>
