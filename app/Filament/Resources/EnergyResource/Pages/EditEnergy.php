<?php

namespace App\Filament\Resources\EnergyResource\Pages;

use App\Filament\Resources\EnergyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEnergy extends EditRecord
{
    protected static string $resource = EnergyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
