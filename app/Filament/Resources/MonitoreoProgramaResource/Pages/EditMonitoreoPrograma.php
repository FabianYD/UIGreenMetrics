<?php

namespace App\Filament\Resources\MonitoreoProgramaResource\Pages;

use App\Filament\Resources\MonitoreoProgramaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMonitoreoPrograma extends EditRecord
{
    protected static string $resource = MonitoreoProgramaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
