<?php

namespace App\Filament\Resources\DispositivoEficienteResource\Pages;

use App\Filament\Resources\DispositivoEficienteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDispositivoEficientes extends ListRecords
{
    protected static string $resource = DispositivoEficienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
