<?php

namespace App\Filament\Resources\AguaRecicladaResource\Pages;

use App\Filament\Resources\AguaRecicladaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAguaReciclada extends EditRecord
{
    protected static string $resource = AguaRecicladaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
