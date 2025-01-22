<?php

namespace App\Filament\Resources\TratamientoAguaResource\Pages;

use App\Filament\Resources\TratamientoAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTratamientoAgua extends EditRecord
{
    protected static string $resource = TratamientoAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
