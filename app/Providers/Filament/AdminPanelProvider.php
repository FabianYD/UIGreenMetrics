<?php

namespace App\Providers\Filament;

use Filament\Pages\Dashboard;
use App\Filament\Resources\EmpleadoResource;
use App\Filament\Resources\MedidorAguaResource;
use App\Filament\Resources\MedidorElectricoResource;
use App\Filament\Resources\ConsumoAguaResource;
use App\Filament\Resources\ConsumoEnergiaResource;
use App\Filament\Resources\TratamientoAguaResource;
use App\Filament\Resources\GeneracionEnergiaResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('EcoMetric')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('3rem')
            ->colors([
                'primary' => Color::Emerald,
                'danger' => Color::Rose,
                'gray' => Color::Slate,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
            ])
            ->font('Poppins')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->resources([
                EmpleadoResource::class,
                MedidorAguaResource::class,
                MedidorElectricoResource::class,
                ConsumoAguaResource::class,
                ConsumoEnergiaResource::class,
                TratamientoAguaResource::class,
                GeneracionEnergiaResource::class,
            ])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Pages\EditProfile::class,
            ])
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
                \App\Filament\Widgets\StatsGreenMetricOverview::class,
                \App\Filament\Widgets\ComparacionConsumoAguaChar::class,
                \App\Filament\Widgets\ComparacionConsumoEnergiaChar::class,
                \App\Filament\Widgets\GreenMetricRankingStats::class,
                \App\Filament\Widgets\ConsumosChart::class,
               # \App\Filament\Widgets\TiposdeTratamiento::class,
                
                #\App\Filament\Widgets\EnergiaRenovableChart::class,
                \App\Filament\Widgets\ConsumoPorCampusChart::class,
                #\App\Filament\Widgets\TendenciaMensualChart::class,
               \App\Filament\Widgets\AlertaConsumoAgua::class,
               #\App\Filament\Widgets\AlertaConsumoElectricidad::class,

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class, 
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->maxContentWidth('full')
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                'Gestión de Personal',
                'Gestión de Agua',
                'Gestión de Energía',
                'Administración',
                'Cuenta',
            ])  
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->renderHook(
                'panels::styles.before',
                fn (): string => '
                    <link rel="preconnect" href="https://fonts.googleapis.com">
                    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
                    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
                ');
    }
}
