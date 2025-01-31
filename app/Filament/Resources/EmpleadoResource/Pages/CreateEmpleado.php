<?php

namespace App\Filament\Resources\EmpleadoResource\Pages;

use App\Filament\Resources\EmpleadoResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;

class CreateEmpleado extends CreateRecord
{
    protected static string $resource = EmpleadoResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = static::getModel()::create($data);
        $record->sincronizarUsuario(); // Esto creará el usuario con la contraseña por defecto (DNI)

        Notification::make()
            ->success()
            ->title('Empleado creado')
            ->body("El empleado ha sido creado exitosamente. La contraseña inicial del usuario es su número de DNI ({$record->EMP_DNI})")
            ->send();

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
