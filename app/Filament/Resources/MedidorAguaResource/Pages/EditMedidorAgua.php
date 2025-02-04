<?php

namespace App\Filament\Resources\MedidorAguaResource\Pages;

use App\Filament\Resources\MedidorAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedidorAgua extends EditRecord
{
    protected static string $resource = MedidorAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
