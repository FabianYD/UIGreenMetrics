<?php

namespace App\Filament\Resources\MonitoreoProgramaResource\Pages;

use App\Filament\Resources\MonitoreoProgramaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMonitoreoProgramas extends ListRecords
{
    protected static string $resource = MonitoreoProgramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
