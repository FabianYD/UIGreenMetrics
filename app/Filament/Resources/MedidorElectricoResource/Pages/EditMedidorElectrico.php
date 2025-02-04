<?php

namespace App\Filament\Resources\MedidorElectricoResource\Pages;

use App\Filament\Resources\MedidorElectricoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedidorElectrico extends EditRecord
{
    protected static string $resource = MedidorElectricoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
