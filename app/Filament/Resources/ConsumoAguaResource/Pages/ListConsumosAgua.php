<?php

namespace App\Filament\Resources\ConsumoAguaResource\Pages;

use App\Filament\Resources\ConsumoAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumosAgua extends ListRecords
{
    protected static string $resource = ConsumoAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
