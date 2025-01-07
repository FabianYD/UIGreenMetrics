<?php

namespace App\Filament\Resources\EnergyResource\Pages;

use App\Filament\Resources\EnergyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEnergies extends ListRecords
{
    protected static string $resource = EnergyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
