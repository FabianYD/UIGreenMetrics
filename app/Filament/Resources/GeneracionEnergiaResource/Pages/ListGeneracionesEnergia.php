<?php

namespace App\Filament\Resources\GeneracionEnergiaResource\Pages;

use App\Filament\Resources\GeneracionEnergiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGeneracionesEnergia extends ListRecords
{
    protected static string $resource = GeneracionEnergiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
