<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Models\Empleado;

class EditProfile extends Page implements Forms\Contracts\HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Mi Perfil';
    protected static string $view = 'filament.pages.edit-profile';

    public function mount(): void
    {
        $user = auth()->user();
        $empleado = Empleado::where('EMP_EMAIL', $user->email)->first();

        if (!$empleado) {
            Notification::make()
                ->warning()
                ->title('Error')
                ->body('No se encontró información de empleado asociada a tu cuenta.')
                ->send();
            
            $this->redirect('/admin');
            return;
        }

        $this->form->fill($empleado->toArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información Personal')
                    ->description('Actualiza tu información personal')
                    ->schema([
                        Forms\Components\TextInput::make('EMP_NOMBRES')
                            ->label('Nombres')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('EMP_APELLIDOS')
                            ->label('Apellidos')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('EMP_CODIGO')
                            ->label('Código')
                            ->maxLength(7),
                        Forms\Components\TextInput::make('EMP_EMAIL')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(100),
                    ])->columns(2),

                Section::make('Cambiar Contraseña')
                    ->description('Si deseas cambiar tu contraseña, ingresa una nueva')
                    ->schema([
                        Forms\Components\TextInput::make('new_password')
                            ->label('Nueva Contraseña')
                            ->password()
                            ->rules(['confirmed'])
                            ->autocomplete('new-password'),
                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label('Confirmar Nueva Contraseña')
                            ->password()
                            ->rules([
                                'required_with:new_password',
                            ])
                            ->autocomplete('new-password'),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    public function submit(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();
        $empleado = Empleado::where('EMP_EMAIL', $user->email)->first();

        if (!$empleado) {
            Notification::make()
                ->danger()
                ->title('Error')
                ->body('No se encontró el empleado.')
                ->send();
            return;
        }

        $empleado->update([
            'EMP_NOMBRES' => $data['EMP_NOMBRES'],
            'EMP_APELLIDOS' => $data['EMP_APELLIDOS'],
            'EMP_CODIGO' => $data['EMP_CODIGO'],
            'EMP_EMAIL' => $data['EMP_EMAIL'],
        ]);

        $user->update([
            'name' => $data['EMP_NOMBRES'] . ' ' . $data['EMP_APELLIDOS'],
            'email' => $data['EMP_EMAIL'],
        ]);

        if (!empty($data['new_password'])) {
            $user->update([
                'password' => Hash::make($data['new_password']),
            ]);
        }

        Notification::make()
            ->success()
            ->title('Perfil actualizado')
            ->body('Tu información ha sido actualizada exitosamente.')
            ->send();

        $this->redirect('/admin');
    }
}
