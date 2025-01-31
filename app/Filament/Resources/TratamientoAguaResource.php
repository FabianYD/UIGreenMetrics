<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TratamientoAguaResource\Pages;
use App\Filament\Resources\Traits\HasRoleRestrictions;
use App\Models\TratamientoAgua;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TratamientoAguaResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = TratamientoAgua::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Tratamiento de Agua';
    protected static ?string $pluralModelLabel = 'tratamientos de agua';
    protected static ?string $modelLabel = 'tratamiento de agua';

    protected static function getAllowedRoles(): array
    {
        return ['TAG', 'ADM'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('CONSAG_ID')
                            ->label('Consumo de Agua')
                            ->relationship('consumo', 'CONSAG_ID')
                            ->required(),
                        Forms\Components\Select::make('TIPOTRA_COD')
                            ->label('Tipo de Tratamiento')
                            ->relationship('tipoTratamiento', 'TIPOTRA_NOMBRES')
                            ->required(),
                        Forms\Components\TextInput::make('TRAGUA_TOTAL')
                            ->label('Total Tratado')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consumo.CONSAG_ID')
                    ->label('ID Consumo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipoTratamiento.TIPOTRA_NOMBRES')
                    ->label('Tipo de Tratamiento')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('TRAGUA_TOTAL')
                    ->label('Total Tratado')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('TIPOTRA_COD')
                    ->label('Tipo de Tratamiento')
                    ->relationship('tipoTratamiento', 'TIPOTRA_NOMBRES'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTratamientosAgua::route('/'),
            'create' => Pages\CreateTratamientoAgua::route('/create'),
            'edit' => Pages\EditTratamientoAgua::route('/{record}/edit'),
        ];
    }
}
