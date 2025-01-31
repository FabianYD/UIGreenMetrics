<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmpleadoResource\Pages;
use App\Models\Empleado;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class EmpleadoResource extends Resource
{
    protected static ?string $model = Empleado::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Gestión de Personal';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Empleados';
    protected static ?string $pluralModelLabel = 'empleados';
    protected static ?string $modelLabel = 'empleado';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('EMP_DNI')
                            ->label('DNI')
                            ->required()
                            ->maxLength(10)
                            ->unique(ignoreRecord: true)
                            ->helperText('Este número será la contraseña inicial del usuario'),
                        Forms\Components\Select::make('ROL_COD')
                            ->label('Rol')
                            ->relationship('rol', 'ROL_DETALLE')
                            ->required(),
                        Forms\Components\Select::make('UNI_ID')
                            ->label('Universidad')
                            ->relationship('universidad', 'UNI_NOMBRES')
                            ->required(),
                        Forms\Components\TextInput::make('EMP_CODIGO')
                            ->label('Código')
                            ->maxLength(7),
                        Forms\Components\TextInput::make('EMP_NOMBRES')
                            ->label('Nombres')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('EMP_APELLIDOS')
                            ->label('Apellidos')
                            ->required()
                            ->maxLength(50),
                        Forms\Components\TextInput::make('EMP_EMAIL')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(100),
                    ])
                    ->columns(2)
                    ->description('Al crear el empleado, se creará automáticamente un usuario con el número de DNI como contraseña inicial.')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('EMP_DNI')
                    ->label('DNI')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EMP_CODIGO')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EMP_NOMBRES')
                    ->label('Nombres')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('EMP_APELLIDOS')
                    ->label('Apellidos')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rol.ROL_DETALLE')
                    ->label('Rol')
                    ->sortable(),
                Tables\Columns\TextColumn::make('universidad.UNI_NOMBRES')
                    ->label('Universidad')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('ROL_COD')
                    ->label('Rol')
                    ->relationship('rol', 'ROL_DETALLE'),
                Tables\Filters\SelectFilter::make('UNI_ID')
                    ->label('Universidad')
                    ->relationship('universidad', 'UNI_NOMBRES'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('cambiarPassword')
                    ->label('Cambiar Contraseña')
                    ->form([
                        Forms\Components\TextInput::make('password')
                            ->label('Nueva Contraseña')
                            ->password()
                            ->required()
                            ->minLength(6),
                        Forms\Components\TextInput::make('password_confirmation')
                            ->label('Confirmar Contraseña')
                            ->password()
                            ->required()
                            ->same('password'),
                        Forms\Components\Toggle::make('reset_default')
                            ->label('Restablecer a contraseña por defecto (número de DNI)')
                            ->default(false),
                    ])
                    ->action(function (Empleado $record, array $data): void {
                        if ($data['reset_default']) {
                            $record->sincronizarUsuario($record->EMP_DNI);
                            $mensaje = 'La contraseña ha sido restablecida al número de DNI.';
                        } else {
                            $record->sincronizarUsuario($data['password']);
                            $mensaje = 'La contraseña ha sido actualizada exitosamente.';
                        }

                        Notification::make()
                            ->success()
                            ->title('Contraseña actualizada')
                            ->body($mensaje)
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmpleados::route('/'),
            'create' => Pages\CreateEmpleado::route('/create'),
            'edit' => Pages\EditEmpleado::route('/{record}/edit'),
        ];
    }
}
