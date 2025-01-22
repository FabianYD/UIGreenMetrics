<?php

namespace App\Filament\Resources\ConsumoAguaResource\Pages;

use App\Filament\Resources\ConsumoAguaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumoAgua extends EditRecord
{
    protected static string $resource = ConsumoAguaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
