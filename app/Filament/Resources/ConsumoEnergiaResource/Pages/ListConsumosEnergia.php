<?php

namespace App\Filament\Resources\ConsumoEnergiaResource\Pages;

use App\Filament\Resources\ConsumoEnergiaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumosEnergia extends ListRecords
{
    protected static string $resource = ConsumoEnergiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
