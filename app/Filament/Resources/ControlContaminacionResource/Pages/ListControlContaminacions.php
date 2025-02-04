<?php

namespace App\Filament\Resources\ControlContaminacionResource\Pages;

use App\Filament\Resources\ControlContaminacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListControlContaminacions extends ListRecords
{
    protected static string $resource = ControlContaminacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
