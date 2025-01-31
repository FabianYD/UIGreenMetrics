<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GeneracionEnergiaResource\Pages;
use App\Filament\Resources\Traits\HasRoleRestrictions;
use App\Models\GeneracionEnergia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GeneracionEnergiaResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = GeneracionEnergia::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Gestión de Energía';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Generación de Energía';
    protected static ?string $pluralModelLabel = 'generaciones de energía';
    protected static ?string $modelLabel = 'generación de energía';

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
                        Forms\Components\Select::make('GENTYPE_ID')
                            ->label('Tipo de Generación')
                            ->relationship('tipoGeneracion', 'GENTYPE_DETALLE')
                            ->required(),
                        Forms\Components\Select::make('FACU_CODIGO')
                            ->label('Facultad')
                            ->relationship('facultad', 'FACU_NOMBRE')
                            ->required(),
                        Forms\Components\TextInput::make('GENENE_TOTAL')
                            ->label('Total Generado')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\TextInput::make('GENENE_TIPO')
                            ->label('Tipo')
                            ->required()
                            ->maxLength(100),
                        Forms\Components\DatePicker::make('GENENE_FECHA')
                            ->label('Fecha')
                            ->required()
                            ->maxDate(now()),
                        Forms\Components\TextInput::make('GENENE_CONSUMO')
                            ->label('Consumo')
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
                Tables\Columns\TextColumn::make('tipoGeneracion.GENTYPE_DETALLE')
                    ->label('Tipo de Generación')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('facultad.FACU_NOMBRE')
                    ->label('Facultad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('GENENE_TOTAL')
                    ->label('Total Generado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('GENENE_TIPO')
                    ->label('Tipo')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('GENENE_FECHA')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('GENENE_CONSUMO')
                    ->label('Consumo')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('GENTYPE_ID')
                    ->label('Tipo de Generación')
                    ->relationship('tipoGeneracion', 'GENTYPE_DETALLE'),
                Tables\Filters\SelectFilter::make('FACU_CODIGO')
                    ->label('Facultad')
                    ->relationship('facultad', 'FACU_NOMBRE'),
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
            'index' => Pages\ListGeneracionesEnergia::route('/'),
            'create' => Pages\CreateGeneracionEnergia::route('/create'),
            'edit' => Pages\EditGeneracionEnergia::route('/{record}/edit'),
        ];
    }
}
