<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumoEnergiaResource\Pages;
use App\Models\ConsumoEnergia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConsumoEnergiaResource extends Resource
{
    protected static ?string $model = ConsumoEnergia::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Gestión de Energía';
    protected static ?string $navigationLabel = 'Consumo de Energía';
    protected static ?string $pluralModelLabel = 'consumos de energía';
    protected static ?string $modelLabel = 'consumo de energía';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Select::make('IDMEDIDOR2')
                            ->label('Medidor')
                            ->relationship('medidor', 'IDMEDIDOR2')
                            ->required(),
                        Forms\Components\Select::make('TIPOENE_ID')
                            ->label('Tipo de Energía')
                            ->relationship('tipoEnergia', 'TIPOENE_NOMBRES')
                            ->required(),
                        Forms\Components\Select::make('MEDENE_COD')
                            ->label('Unidad de Medida')
                            ->relationship('unidadMedida', 'MEDENE_NOMBRE')
                            ->required(),
                        Forms\Components\TextInput::make('CONSENE_TOTAL')
                            ->label('Total Consumido')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\DatePicker::make('CONSENE_FECHAPAGO')
                            ->label('Fecha de Pago')
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
                Tables\Columns\TextColumn::make('medidor.IDMEDIDOR2')
                    ->label('Medidor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipoEnergia.TIPOENE_NOMBRES')
                    ->label('Tipo de Energía')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidadMedida.MEDENE_NOMBRE')
                    ->label('Unidad de Medida')
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONSENE_TOTAL')
                    ->label('Total Consumido')
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONSENE_FECHAPAGO')
                    ->label('Fecha de Pago')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('IDMEDIDOR2')
                    ->label('Medidor')
                    ->relationship('medidor', 'IDMEDIDOR2'),
                Tables\Filters\SelectFilter::make('TIPOENE_ID')
                    ->label('Tipo de Energía')
                    ->relationship('tipoEnergia', 'TIPOENE_NOMBRES'),
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
            'index' => Pages\ListConsumosEnergia::route('/'),
            'create' => Pages\CreateConsumoEnergia::route('/create'),
            'edit' => Pages\EditConsumoEnergia::route('/{record}/edit'),
        ];
    }
}
