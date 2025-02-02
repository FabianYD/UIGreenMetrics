<?php

namespace App\Filament\Resources\ProgramaConservacionResource\Pages;

use App\Filament\Resources\ProgramaConservacionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgramaConservacion extends EditRecord
{
    protected static string $resource = ProgramaConservacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
