<?php

namespace App\Filament\Resources\GeneracionEnergiaResource\Pages;

use App\Filament\Resources\GeneracionEnergiaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGeneracionEnergia extends EditRecord
{
    protected static string $resource = GeneracionEnergiaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
