<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class InfraestructuraDesechos extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-building-library';
    protected static ?string $navigationGroup = 'Sistemas Externos';
    protected static ?string $navigationLabel = 'Infraestructura y Desechos';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.infraestructura-desechos';
}
