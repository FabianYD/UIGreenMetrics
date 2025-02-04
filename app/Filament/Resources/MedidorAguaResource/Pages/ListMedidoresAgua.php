<?php

namespace App\Filament\Resources\MedidorAguaResource\Pages;

use App\Filament\Resources\MedidorAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMedidoresAgua extends ListRecords
{
    protected static string $resource = MedidorAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
