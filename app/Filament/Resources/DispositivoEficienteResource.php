<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispositivoEficienteResource\Pages;
use App\Models\DispositivoEficiente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class DispositivoEficienteResource extends Resource
{
    protected static ?string $model = DispositivoEficiente::class;
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Dispositivos Eficientes';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('CAMPUS_ID')
                    ->relationship('campus', 'CAMPUS_NOMBRES')
                    ->required()
                    ->label('Campus'),
                Forms\Components\Select::make('FACU_CODIGO')
                    ->relationship('facultad', 'FACU_NOMBRE')
                    ->required()
                    ->label('Facultad'),
                Forms\Components\Select::make('DISPEF_TIPO')
                    ->required()
                    ->label('Tipo de Dispositivo')
                    ->options([
                        'grifo_sensor' => 'Grifo con Sensor',
                        'inodoro_dual' => 'Inodoro de Doble Descarga',
                        'urinario_seco' => 'Urinario Seco',
                        'regadera_ahorradora' => 'Regadera Ahorradora',
                        'medidor_inteligente' => 'Medidor Inteligente',
                        'otro' => 'Otro'
                    ]),
                Forms\Components\TextInput::make('DISPEF_UBICACION')
                    ->required()
                    ->label('Ubicación')
                    ->maxLength(100),
                Forms\Components\TextInput::make('DISPEF_EFICIENCIA')
                    ->required()
                    ->numeric()
                    ->label('Eficiencia (%)')
                    ->minValue(0)
                    ->maxValue(100)
                    ->step(0.01),
                Forms\Components\DatePicker::make('DISPEF_FECHAINSTALACION')
                    ->required()
                    ->label('Fecha de Instalación'),
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
                Tables\Columns\TextColumn::make('DISPEF_NOMBRE')
                    ->label('Dispositivo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DISPEF_TIPO')
                    ->label('Tipo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DISPEF_ESTADO')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'mantenimiento' => 'warning',
                        'inactivo' => 'danger',
                        default => 'secondary',
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function ($record) {
                        $html = "
                            <div class='space-y-4'>
                                <div class='text-xl font-bold'>Detalles del Dispositivo Eficiente</div>
                                
                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Campus</div>
                                        <div>{$record->campus->CAMPUS_NOMBRES}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Nombre del Dispositivo</div>
                                        <div>{$record->DISPEF_NOMBRE}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Tipo</div>
                                        <div>{$record->DISPEF_TIPO}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Estado</div>
                                        <div>{$record->DISPEF_ESTADO}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Fecha de Instalación</div>
                                        <div>{$record->DISPEF_FECHAINSTALACION->format('d/m/Y')}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Última Revisión</div>
                                        <div>" . ($record->DISPEF_FECHAULTIMAREVISION ? $record->DISPEF_FECHAULTIMAREVISION->format('d/m/Y') : 'Sin revisión') . "</div>
                                    </div>
                                </div>

                                <div>
                                    <div class='font-semibold'>Ubicación</div>
                                    <div class='mt-1'>{$record->DISPEF_UBICACION}</div>
                                </div>

                                <div>
                                    <div class='font-semibold'>Características</div>
                                    <div class='mt-1'>{$record->DISPEF_CARACTERISTICAS}</div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold'>Mantenimiento</div>
                                    <div class='mt-1'>
                                        <div>Frecuencia: {$record->DISPEF_FRECUENCIAMANTENIMIENTO}</div>
                                        <div>Próximo mantenimiento: " . ($record->DISPEF_FECHAPROXIMAREVISION ? $record->DISPEF_FECHAPROXIMAREVISION->format('d/m/Y') : 'No programado') . "</div>
                                    </div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold'>Eficiencia</div>
                                    <div class='mt-1'>
                                        <div>Consumo anterior: {$record->DISPEF_CONSUMOANTERIOR} kWh/mes</div>
                                        <div>Consumo actual: {$record->DISPEF_CONSUMOACTUAL} kWh/mes</div>
                                        <div>Ahorro estimado: {$record->DISPEF_AHORROESTIMADO}%</div>
                                    </div>
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
                Tables\Filters\SelectFilter::make('CAMPUS_ID')
                    ->relationship('campus', 'CAMPUS_NOMBRES')
                    ->label('Campus'),
                Tables\Filters\SelectFilter::make('FACU_CODIGO')
                    ->relationship('facultad', 'FACU_NOMBRE')
                    ->label('Facultad'),
                Tables\Filters\SelectFilter::make('DISPEF_TIPO')
                    ->label('Tipo de Dispositivo')
                    ->options([
                        'grifo_sensor' => 'Grifo con Sensor',
                        'inodoro_dual' => 'Inodoro de Doble Descarga',
                        'urinario_seco' => 'Urinario Seco',
                        'regadera_ahorradora' => 'Regadera Ahorradora',
                        'medidor_inteligente' => 'Medidor Inteligente',
                        'otro' => 'Otro'
                    ]),
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
            'index' => Pages\ListDispositivoEficientes::route('/'),
            'create' => Pages\CreateDispositivoEficiente::route('/create'),
            'edit' => Pages\EditDispositivoEficiente::route('/{record}/edit'),
        ];
    }
}
