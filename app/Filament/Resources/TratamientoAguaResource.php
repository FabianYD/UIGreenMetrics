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
use Illuminate\Support\HtmlString;

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
                            ->relationship(
                                'consumo',
                                'CONSAG_ID',
                                fn ($query) => $query->with(['medidorAgua.campus'])
                            )
                            ->getOptionLabelFromRecordUsing(fn ($record) => 
                                "ID: {$record->CONSAG_ID} - Campus: {$record->medidorAgua->campus->CAMPUS_NOMBRES} - " .
                                "Total: {$record->CONSAG_TOTAL} m³ - Fecha: {$record->CONSENE_FECHAPAGO}"
                            )
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\Select::make('TIPOTRA_COD')
                            ->label('Tipo de Tratamiento')
                            ->relationship('tipoTratamiento', 'TIPOTRA_NOMBRES')
                            ->required(),
                        Forms\Components\TextInput::make('TRAGUA_TOTAL')
                            ->label('Total de agua tratada (m³)')
                            ->numeric()
                            ->required()
                            ->rules([
                                fn (Forms\Get $get): \Closure => function (string $attribute, $value, \Closure $fail) use ($get) {
                                    $consumo = \App\Models\ConsumoAgua::find($get('CONSAG_ID'));
                                    if ($consumo && $value > $consumo->CONSAG_TOTAL) {
                                        $fail("La cantidad tratada no puede ser mayor que el consumo total ({$consumo->CONSAG_TOTAL} m³)");
                                    }
                                },
                            ]),
                        Forms\Components\Placeholder::make('porcentajes')
                            ->label('Porcentajes')
                            ->content(function ($record) {
                                if (!$record) return 'Guarde primero el registro para ver los porcentajes';
                                
                                $query = $record->aguasRecicladas();
                                $totalReciclado = $query->sum('AGUAREC_CANTIDAD') ?? 0;
                                
                                $html = "• {$record->TRAGUA_PORCENTAJE_TRATADO}% del consumo total de agua<br>";
                                
                                if ($totalReciclado > 0) {
                                    $html .= "• {$totalReciclado} m³ reciclados ({$record->TRAGUA_PORCENTAJE_RECICLADO}% del agua tratada)<br>";
                                    $html .= "<br>Detalles del agua reciclada:<br>";
                                    
                                    $detalles = $query->get()->map(function($item) {
                                        return "- {$item->AGUAREC_CANTIDAD} m³ ({$item->AGUAREC_PORCENTAJE}%) - {$item->AGUAREC_DESTINO} - {$item->AGUAREC_FECHA}";
                                    })->join('<br>');
                                    
                                    $html .= "<small class='text-gray-500'>{$detalles}</small>";
                                } else {
                                    $html .= "• No se ha reciclado agua aún";
                                }
                                
                                return new HtmlString($html);
                            })
                            ->live(),
                        Forms\Components\Select::make('TRAGUA_ESTADO_PROGRAMA')
                            ->label('Estado del programa')
                            ->options([
                                'activo' => 'Activo',
                                'pausado' => 'Pausado',
                                'completado' => 'Completado',
                                'cancelado' => 'Cancelado'
                            ])
                            ->placeholder('Seleccione el estado del programa'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consumo.medidorAgua.campus.CAMPUS_NOMBRES')
                    ->label('Campus')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('tipoTratamiento.TIPOTRA_NOMBRES')
                    ->label('Tipo de Tratamiento')
                    ->sortable(),
                Tables\Columns\TextColumn::make('TRAGUA_TOTAL')
                    ->label('Total tratado (m³)')
                    ->numeric(2)
                    ->sortable(),
                Tables\Columns\TextColumn::make('TRAGUA_PORCENTAJE_TRATADO')
                    ->label('% Tratado del Consumo')
                    ->description('Porcentaje que representa del consumo total')
                    ->numeric(2)
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('aguasRecicladas')
                    ->label('Agua Reciclada')
                    ->formatStateUsing(function ($record) {
                        $query = $record->aguasRecicladas();
                        $totalReciclado = $query->sum('AGUAREC_CANTIDAD') ?? 0;
                        $porcentajeTotal = $record->TRAGUA_PORCENTAJE_RECICLADO;
                        
                        if ($totalReciclado <= 0) {
                            return 'No se ha reciclado agua aún';
                        }
                        
                        $detalles = $query->get()->map(function($item) {
                            return "- {$item->AGUAREC_CANTIDAD} m³ ({$item->AGUAREC_PORCENTAJE}%) - {$item->AGUAREC_DESTINO} - {$item->AGUAREC_FECHA}";
                        })->join('<br>');
                        
                        return "Total: {$totalReciclado} m³ ({$porcentajeTotal}% del agua tratada)<br><small class='text-gray-500'>Detalles:<br>{$detalles}</small>";
                    })
                    ->html(),
                Tables\Columns\TextColumn::make('TRAGUA_ESTADO_PROGRAMA')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'activo' => 'Activo',
                        'pausado' => 'Pausado',
                        'completado' => 'Completado',
                        'cancelado' => 'Cancelado',
                        default => $state,
                    }),
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
