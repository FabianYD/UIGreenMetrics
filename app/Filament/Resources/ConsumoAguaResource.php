<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumoAguaResource\Pages;
use App\Models\ConsumoAgua;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ConsumoAguaResource extends Resource
{
    protected static ?string $model = ConsumoAgua::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Consumo de Agua';
    protected static ?string $pluralModelLabel = 'consumos de agua';
    protected static ?string $modelLabel = 'consumo de agua';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Consumo')
                    ->description('Registre los detalles del consumo de agua')
                    ->icon('heroicon-o-information-circle')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('MEDAG_ID')
                            ->label('Medidor')
                            ->relationship('medidor', 'MEDAG_ID')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('MEDIDADAG_COD')
                            ->label('Unidad de Medida')
                            ->relationship('unidadMedida', 'MEDIDAAGU_NOMBRE')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\TextInput::make('CONSAG_TOTAL')
                            ->label('Total Consumido')
                            ->required()
                            ->numeric()
                            ->minValue(0),
                        Forms\Components\DatePicker::make('CONSENE_FECHAPAGO')
                            ->label('Fecha de Pago')
                            ->required()
                            ->maxDate(now()),
                    ]),
                Forms\Components\Section::make('Detalles Adicionales')
                    ->description('Información complementaria del consumo')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Textarea::make('CONSAG_OBSERVACION')
                            ->label('Observaciones')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medidor.MEDAG_ID')
                    ->label('Medidor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONSENE_FECHAPAGO')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONSAG_TOTAL')
                    ->label('Total Consumido')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.'
                    )
                    ->suffix(' m³')
                    ->sortable(),
                Tables\Columns\TextColumn::make('unidadMedida.MEDIDAAGU_NOMBRE')
                    ->label('Unidad')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CONSAG_OBSERVACION')
                    ->label('Observaciones')
                    ->limit(30)
                    ->tooltip(function (Model $record): string {
                        return $record->CONSAG_OBSERVACION ?? '';
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('MEDAG_ID')
                    ->label('Medidor')
                    ->relationship('medidor', 'MEDAG_ID'),
                Tables\Filters\Filter::make('CONSENE_FECHAPAGO')
                    ->label('Fecha de Pago')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['desde'], fn($q) => $q->whereDate('CONSENE_FECHAPAGO', '>=', $data['desde']))
                            ->when($data['hasta'], fn($q) => $q->whereDate('CONSENE_FECHAPAGO', '<=', $data['hasta']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Ver detalles')
                    ->modalHeading('Detalles del Consumo de Agua')
                    ->modalIcon('heroicon-o-information-circle')
                    ->extraAttributes([
                        'class' => 'fi-btn-success'
                    ]),
                Tables\Actions\EditAction::make()
                    ->label('Editar'),
                Tables\Actions\DeleteAction::make()
                    ->label('Eliminar'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Eliminar seleccionados'),
            ])
            ->defaultSort('CONSENE_FECHAPAGO', 'desc')
            ->poll('60s');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsumosAgua::route('/'),
            'create' => Pages\CreateConsumoAgua::route('/create'),
            'edit' => Pages\EditConsumoAgua::route('/{record}/edit'),
        ];
    }
}
