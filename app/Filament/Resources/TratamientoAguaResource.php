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
                    ->label('% Tratado')
                    ->numeric(2)
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('TRAGUA_PORCENTAJE_RECICLADO')
                    ->label('% Reciclado')
                    ->numeric(2)
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('TRAGUA_ESTADO_PROGRAMA')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'pausado' => 'warning',
                        'completado' => 'info',
                        'cancelado' => 'danger',
                        default => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'activo' => 'Activo',
                        'pausado' => 'Pausado',
                        'completado' => 'Completado',
                        'cancelado' => 'Cancelado',
                        default => $state,
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function ($record) {
                        $query = $record->aguasRecicladas();
                        $totalReciclado = $query->sum('AGUAREC_CANTIDAD') ?? 0;
                        
                        $html = "
                            <div class='space-y-4'>
                                <div class='text-xl font-bold'>Detalles del Tratamiento de Agua</div>
                                
                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Campus</div>
                                        <div>{$record->consumo->medidorAgua->campus->CAMPUS_NOMBRES}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Tipo de Tratamiento</div>
                                        <div>{$record->tipoTratamiento->TIPOTRA_NOMBRES}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Total Tratado</div>
                                        <div>{$record->TRAGUA_TOTAL} m³</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Porcentaje del Consumo</div>
                                        <div>{$record->TRAGUA_PORCENTAJE_TRATADO}%</div>
                                    </div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold mb-2'>Agua Reciclada</div>";
                        
                        if ($totalReciclado > 0) {
                            $html .= "
                                    <div class='mb-2'>Total reciclado: {$totalReciclado} m³ ({$record->TRAGUA_PORCENTAJE_RECICLADO}%)</div>
                                    <div class='space-y-2'>";
                            
                            foreach ($query->get() as $item) {
                                $html .= "
                                        <div class='bg-gray-50 p-2 rounded'>
                                            <div>Cantidad: {$item->AGUAREC_CANTIDAD} m³ ({$item->AGUAREC_PORCENTAJE}%)</div>
                                            <div>Destino: {$item->AGUAREC_DESTINO}</div>
                                            <div>Fecha: {$item->AGUAREC_FECHA}</div>
                                        </div>";
                            }
                            
                            $html .= "
                                    </div>";
                        } else {
                            $html .= "
                                    <div class='text-gray-500'>No se ha reciclado agua aún</div>";
                        }
                        
                        $html .= "
                                </div>
                            </div>";
                        
                        return new HtmlString($html);
                    })
                    ->modalWidth('xl')
                    ->modalAlignment('center'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('TIPOTRA_COD')
                    ->label('Tipo de Tratamiento')
                    ->relationship('tipoTratamiento', 'TIPOTRA_NOMBRES'),
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
