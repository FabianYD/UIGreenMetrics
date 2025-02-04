<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ControlContaminacionResource\Pages;
use App\Models\ControlContaminacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                        'planificacion' => 'En Planificación',
                        'implementacion_temprana' => 'Implementación Temprana',
                        'implementacion_completa' => 'Implementación Completa',
                        'evaluacion' => 'En Evaluación',
                        'mantenimiento' => 'En Mantenimiento'
                    ]),
                Forms\Components\DatePicker::make('CONTAM_FECHAINICIO')
                    ->required()
                    ->label('Fecha de Inicio'),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONTAM_TIPO')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'monitoreo_calidad' => 'Monitoreo de Calidad',
                        'tratamiento_quimico' => 'Tratamiento Químico',
                        'filtracion_avanzada' => 'Filtración Avanzada',
                        'control_biologico' => 'Control Biológico',
                        'otro' => 'Otro',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('CONTAM_ESTADO')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'planificacion' => 'En Planificación',
                        'implementacion_temprana' => 'Implementación Temprana',
                        'implementacion_completa' => 'Implementación Completa',
                        'evaluacion' => 'En Evaluación',
                        'mantenimiento' => 'En Mantenimiento',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'planificacion' => 'gray',
                        'implementacion_temprana' => 'warning',
                        'implementacion_completa' => 'success',
                        'evaluacion' => 'info',
                        'mantenimiento' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('CONTAM_FECHAINICIO')
                    ->label('Fecha Inicio')
                    ->date()
                    ->sortable(),
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
                        'planificacion' => 'En Planificación',
                        'implementacion_temprana' => 'Implementación Temprana',
                        'implementacion_completa' => 'Implementación Completa',
                        'evaluacion' => 'En Evaluación',
                        'mantenimiento' => 'En Mantenimiento'
                    ]),
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
            'index' => Pages\ListControlContaminacions::route('/'),
            'create' => Pages\CreateControlContaminacion::route('/create'),
            'edit' => Pages\EditControlContaminacion::route('/{record}/edit'),
        ];
    }
}
