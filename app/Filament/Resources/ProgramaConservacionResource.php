<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramaConservacionResource\Pages;
use App\Models\ProgramaConservacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class ProgramaConservacionResource extends Resource
{
    protected static ?string $model = ProgramaConservacion::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Programas de Conservación';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('CAMPUS_ID')
                    ->relationship('campus', 'CAMPUS_NOMBRES')
                    ->label('Campus')
                    ->required(),
                Forms\Components\TextInput::make('PROGCONS_NOMBRE')
                    ->label('Nombre del Programa')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Textarea::make('PROGCONS_DESCRIPCION')
                    ->label('Descripción')
                    ->maxLength(65535),
                Forms\Components\Select::make('PROGCONS_ESTADO')
                    ->label('Estado del Programa')
                    ->options([
                        'planificacion' => 'Planificación',
                        'implementacion' => 'Implementación',
                        'evaluacion' => 'Evaluación',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('PROGCONS_FECHAINICIO')
                    ->label('Fecha de Inicio')
                    ->required(),
                Forms\Components\DatePicker::make('PROGCONS_FECHAFIN')
                    ->label('Fecha de Finalización'),
                Forms\Components\TextInput::make('PROGCONS_AVANCE')
                    ->label('Porcentaje de Avance')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->suffix('%'),
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
                Tables\Columns\TextColumn::make('PROGCONS_NOMBRE')
                    ->label('Nombre del Programa')
                    ->searchable(),
                Tables\Columns\TextColumn::make('PROGCONS_ESTADO')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'planificacion' => 'Planificación',
                        'implementacion' => 'Implementación',
                        'evaluacion' => 'Evaluación',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('PROGCONS_AVANCE')
                    ->label('Avance')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('PROGCONS_FECHAINICIO')
                    ->label('Fecha de Inicio')
                    ->date(),
                Tables\Columns\TextColumn::make('PROGCONS_FECHAFIN')
                    ->label('Fecha de Finalización')
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function ($record) {
                        $monitoreos = $record->monitoreoProgramas()->orderBy('MONPRO_FECHA', 'desc')->take(5)->get();
                        
                        $html = "
                            <div class='space-y-4'>
                                <div class='text-xl font-bold'>Detalles del Programa de Conservación</div>
                                
                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Campus</div>
                                        <div>{$record->campus->CAMPUS_NOMBRES}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Nombre del Programa</div>
                                        <div>{$record->PROGCONS_NOMBRE}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Fecha Inicio</div>
                                        <div>{$record->PROGCONS_FECHAINICIO->format('d/m/Y')}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Fecha Fin</div>
                                        <div>" . ($record->PROGCONS_FECHAFIN ? $record->PROGCONS_FECHAFIN->format('d/m/Y') : 'En curso') . "</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Estado</div>
                                        <div>{$record->PROGCONS_ESTADO}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Presupuesto</div>
                                        <div>$ {$record->PROGCONS_PRESUPUESTO}</div>
                                    </div>
                                </div>

                                <div>
                                    <div class='font-semibold'>Descripción</div>
                                    <div class='mt-1'>{$record->PROGCONS_DESCRIPCION}</div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold mb-2'>Monitoreos Recientes</div>";
                        
                        if ($monitoreos->count() > 0) {
                            $html .= "<div class='space-y-2'>";
                            foreach ($monitoreos as $monitoreo) {
                                $html .= "
                                    <div class='bg-gray-50 p-2 rounded'>
                                        <div>Fecha: {$monitoreo->MONPRO_FECHA->format('d/m/Y')}</div>
                                        <div>Resultado: {$monitoreo->MONPRO_RESULTADO}</div>
                                        <div>Observaciones: {$monitoreo->MONPRO_OBSERVACIONES}</div>
                                    </div>";
                            }
                            $html .= "</div>";
                        } else {
                            $html .= "<div class='text-gray-500'>No hay monitoreos registrados</div>";
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
                //
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
            'index' => Pages\ListProgramaConservacions::route('/'),
            'create' => Pages\CreateProgramaConservacion::route('/create'),
            'edit' => Pages\EditProgramaConservacion::route('/{record}/edit'),
        ];
    }
}
