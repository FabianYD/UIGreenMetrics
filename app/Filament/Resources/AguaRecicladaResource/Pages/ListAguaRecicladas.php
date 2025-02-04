<?php

namespace App\Filament\Resources\AguaRecicladaResource\Pages;

use App\Filament\Resources\AguaRecicladaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAguaRecicladas extends ListRecords
{
    protected static string $resource = AguaRecicladaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
