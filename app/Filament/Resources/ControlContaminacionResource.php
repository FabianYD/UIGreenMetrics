<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ControlContaminacionResource\Pages;
use App\Models\ControlContaminacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ControlContaminacionResource extends Resource
{
    protected static ?string $model = ControlContaminacion::class;
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Control de Contaminación';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('CAMPUS_ID')
                    ->relationship('campus', 'CAMPUS_NOMBRES')
                    ->required()
                    ->label('Campus'),
                Forms\Components\Select::make('CONTAM_TIPO')
                    ->required()
                    ->label('Tipo de Control')
                    ->options([
                        'monitoreo_calidad' => 'Monitoreo de Calidad',
                        'tratamiento_quimico' => 'Tratamiento Químico',
                        'filtracion_avanzada' => 'Filtración Avanzada',
                        'control_biologico' => 'Control Biológico',
                        'otro' => 'Otro'
                    ]),
                Forms\Components\Select::make('CONTAM_ESTADO')
                    ->required()
                    ->label('Estado')
                    ->options([
                        'planificacion' => 'Planificación',
                        'implementacion_completa' => 'Implementación Completa',
                        'mantenimiento' => 'Mantenimiento',
                    ]),
                Forms\Components\DatePicker::make('CONTAM_FECHAINICIO')
                    ->required()
                    ->label('Fecha'),
                Forms\Components\Textarea::make('CONTAM_DESCRIPCION')
                    ->required()
                    ->label('Descripción')
                    ->maxLength(65535),
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
                Tables\Columns\TextColumn::make('CONTAM_TIPO')
                    ->label('Tipo de Control')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CONTAM_FECHAINICIO')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONTAM_ESTADO')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planificacion' => 'warning',
                        'implementacion_completa' => 'success',
                        'mantenimiento' => 'danger',
                        default => 'secondary',
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function ($record) {
                        $html = "
                            <div class='space-y-4'>
                                <div class='text-xl font-bold'>Detalles del Control de Contaminación</div>
                                
                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Campus</div>
                                        <div>{$record->campus->CAMPUS_NOMBRES}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Tipo de Control</div>
                                        <div>{$record->CONTAM_TIPO}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Fecha</div>
                                        <div>{$record->CONTAM_FECHAINICIO->format('d/m/Y')}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Estado</div>
                                        <div>{$record->CONTAM_ESTADO}</div>
                                    </div>
                                </div>

                                <div>
                                    <div class='font-semibold'>Ubicación</div>
                                    <div class='mt-1'>{$record->CONTAM_UBICACION}</div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold'>Mediciones</div>
                                    <div class='grid grid-cols-2 gap-4 mt-2'>
                                        <div>
                                            <div class='font-semibold'>Nivel Medido</div>
                                            <div>{$record->CONTAM_NIVELMEDIDO} {$record->CONTAM_UNIDADMEDIDA}</div>
                                        </div>
                                        <div>
                                            <div class='font-semibold'>Nivel Permitido</div>
                                            <div>{$record->CONTAM_NIVELPERMITIDO} {$record->CONTAM_UNIDADMEDIDA}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold'>Observaciones</div>
                                    <div class='mt-1'>{$record->CONTAM_OBSERVACIONES}</div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold'>Acciones Correctivas</div>
                                    <div class='mt-1'>" . ($record->CONTAM_ACCIONESCORRECTIVAS ? $record->CONTAM_ACCIONESCORRECTIVAS : 'No se han registrado acciones correctivas') . "</div>
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
                Tables\Filters\SelectFilter::make('CONTAM_TIPO')
                    ->label('Tipo de Control')
                    ->options([
                        'monitoreo_calidad' => 'Monitoreo de Calidad',
                        'tratamiento_quimico' => 'Tratamiento Químico',
                        'filtracion_avanzada' => 'Filtración Avanzada',
                        'control_biologico' => 'Control Biológico',
                        'otro' => 'Otro'
                    ]),
                Tables\Filters\SelectFilter::make('CONTAM_ESTADO')
                    ->label('Estado')
                    ->options([
                        'planificacion' => 'Planificación',
                        'implementacion_completa' => 'Implementación Completa',
                        'mantenimiento' => 'Mantenimiento',
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
            'index' => Pages\ListControlContaminacions::route('/'),
            'create' => Pages\CreateControlContaminacion::route('/create'),
            'edit' => Pages\EditControlContaminacion::route('/{record}/edit'),
        ];
    }
}
