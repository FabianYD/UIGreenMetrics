<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AguaRecicladaResource\Pages;
use App\Models\AguaReciclada;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AguaRecicladaResource extends Resource
{
    protected static ?string $model = AguaReciclada::class;
    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Agua Reciclada';
    protected static ?int $navigationSort = 4;

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $tratamiento = \App\Models\TratamientoAgua::with('consumo.medidorAgua')->find($data['TRAGUA_ID']);
        if ($tratamiento) {
            $data['CAMPUS_ID'] = $tratamiento->consumo->medidorAgua->CAMPUS_ID;
        }
        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('CAMPUS_ID'),
                Forms\Components\Select::make('TRAGUA_ID')
                    ->relationship(
                        'tratamiento', 
                        'TRAGUA_ID',
                        fn ($query) => $query
                            ->select(['TRAGUA_ID', 'TRAGUA_TOTAL', 'TIPOTRA_COD', 'CONSAG_ID'])
                            ->with(['tipoTratamiento', 'consumo.medidorAgua.campus'])
                            ->orderBy('TRAGUA_ID', 'desc')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => 
                        "ID: {$record->TRAGUA_ID} - Campus: {$record->consumo->medidorAgua->campus->CAMPUS_NOMBRES} - " .
                        "Tipo: {$record->tipoTratamiento->TIPOTRA_NOMBRES} - " .
                        "Total tratado: {$record->TRAGUA_TOTAL} m³"
                    )
                    ->required()
                    ->label('Seleccione el agua tratada a reciclar')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $tratamiento = \App\Models\TratamientoAgua::with('consumo.medidorAgua')
                                ->find($state);
                            if ($tratamiento) {
                                $set('max_cantidad', $tratamiento->TRAGUA_TOTAL);
                                $set('CAMPUS_ID', $tratamiento->consumo->medidorAgua->CAMPUS_ID);
                            }
                        }
                    }),
                Forms\Components\DatePicker::make('AGUAREC_FECHA')
                    ->required()
                    ->label('Fecha de reciclaje'),
                Forms\Components\TextInput::make('AGUAREC_CANTIDAD')
                    ->label('Cantidad reciclada (m³)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->afterStateUpdated(function ($state, $record, Forms\Set $set) {
                        if (!$record) return;
                        
                        $tratamiento = $record->tratamiento;
                        if ($tratamiento && $tratamiento->TRAGUA_TOTAL > 0) {
                            $porcentaje = round(($state / $tratamiento->TRAGUA_TOTAL) * 100, 2);
                            $set('AGUAREC_PORCENTAJE', $porcentaje);
                        }
                    }),
                Forms\Components\TextInput::make('AGUAREC_PORCENTAJE')
                    ->label('Porcentaje del tratamiento (%)')
                    ->disabled()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01),
                Forms\Components\TextInput::make('AGUAREC_DESTINO')
                    ->required()
                    ->label('Destino del agua reciclada')
                    ->placeholder('Ej: Riego de jardines, Sanitarios, Limpieza')
                    ->helperText('Ejemplos: Riego de jardines, Sanitarios, Limpieza')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tratamiento.consumo.medidorAgua.campus.CAMPUS_NOMBRES')
                    ->label('Campus')
                    ->sortable(),
                Tables\Columns\TextColumn::make('AGUAREC_FECHA')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tratamiento.tipoTratamiento.TIPOTRA_NOMBRES')
                    ->label('Tipo de Tratamiento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('AGUAREC_CANTIDAD')
                    ->label('Cantidad (m³)')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('AGUAREC_DESTINO')
                    ->label('Destino')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tratamiento')
                    ->relationship('tratamiento.consumo.medidorAgua.campus', 'CAMPUS_NOMBRES')
                    ->label('Campus'),
                Tables\Filters\Filter::make('AGUAREC_FECHA')
                    ->form([
                        Forms\Components\DatePicker::make('fecha_desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('fecha_hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['fecha_desde'],
                                fn (Builder $query, $date): Builder => $query->whereDate('AGUAREC_FECHA', '>=', $date),
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('AGUAREC_FECHA', '<=', $date),
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAguaRecicladas::route('/'),
            'create' => Pages\CreateAguaReciclada::route('/create'),
            'edit' => Pages\EditAguaReciclada::route('/{record}/edit'),
        ];
    }
}
