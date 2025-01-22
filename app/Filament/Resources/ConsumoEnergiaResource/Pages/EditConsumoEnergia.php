<?php

namespace App\Filament\Resources\ConsumoEnergiaResource\Pages;

use App\Filament\Resources\ConsumoEnergiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumoEnergia extends EditRecord
{
    protected static string $resource = ConsumoEnergiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
