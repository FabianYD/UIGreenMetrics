<?php

namespace App\Filament\Resources\TratamientoAguaResource\Pages;

use App\Filament\Resources\TratamientoAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTratamientosAgua extends ListRecords
{
    protected static string $resource = TratamientoAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
