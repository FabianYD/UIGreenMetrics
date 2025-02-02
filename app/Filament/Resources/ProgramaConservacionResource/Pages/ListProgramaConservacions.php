<?php

namespace App\Filament\Resources\ProgramaConservacionResource\Pages;

use App\Filament\Resources\ProgramaConservacionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProgramaConservacions extends ListRecords
{
    protected static string $resource = ProgramaConservacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
