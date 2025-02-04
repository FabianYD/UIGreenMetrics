<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EducacionTransporte extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Sistemas Externos';
    protected static ?string $navigationLabel = 'Educación y Transporte';
    protected static ?int $navigationSort = 2;
    protected static string $view = 'filament.pages.educacion-transporte';
}
