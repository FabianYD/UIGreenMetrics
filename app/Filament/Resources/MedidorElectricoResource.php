<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedidorElectricoResource\Pages;
use App\Filament\Resources\Traits\HasRoleRestrictions;
use App\Models\MedidorElectrico;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MedidorElectricoResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = MedidorElectrico::class;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Gestión de Energía';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Medidores Eléctricos';
    protected static ?string $pluralModelLabel = 'medidores eléctricos';
    protected static ?string $modelLabel = 'medidor eléctrico';

    protected static function getAllowedRoles(): array
    {
        return ['TEN', 'ADM'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('IDMEDIDOR2')
                            ->label('ID del Medidor')
                            ->required()
                            ->maxLength(14)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('CAMPUS_ID')
                            ->label('Campus')
                            ->relationship('campus', 'CAMPUS_NOMBRES')
                            ->required(),
                        Forms\Components\DatePicker::make('MEDAG_FECHAADQUISICION')
                            ->label('Fecha de Adquisición')
                            ->required()
                            ->maxDate(now()),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('campus.CAMPUS_NOMBRES')
                    ->label('Campus')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('MEDELE_NOMBRE')
                    ->label('Medidor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MEDELE_UBICACION')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MEDELE_ESTADO')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'mantenimiento' => 'warning',
                        'inactivo' => 'danger',
                        default => 'secondary',
                    })
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('CAMPUS_ID')
                    ->label('Campus')
                    ->relationship('campus', 'CAMPUS_NOMBRES'),
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
            'index' => Pages\ListMedidoresElectricos::route('/'),
            'create' => Pages\CreateMedidorElectrico::route('/create'),
            'edit' => Pages\EditMedidorElectrico::route('/{record}/edit'),
        ];
    }
}
