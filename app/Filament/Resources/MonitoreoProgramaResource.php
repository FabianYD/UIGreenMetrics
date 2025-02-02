<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MonitoreoProgramaResource\Pages;
use App\Models\MonitoreoPrograma;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MonitoreoProgramaResource extends Resource
{
    protected static ?string $model = MonitoreoPrograma::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Monitoreo de Programas';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('CAMPUS_ID')
                    ->relationship('campus', 'CAMPUS_NOMBRES')
                    ->required()
                    ->label('Campus'),
                Forms\Components\Select::make('EMP_DNI')
                    ->relationship('empleado', 'EMP_NOMBRES')
                    ->required()
                    ->label('Responsable'),
                Forms\Components\Select::make('MONIT_TIPO')
                    ->required()
                    ->label('Tipo de Monitoreo')
                    ->options([
                        'consumo' => 'Consumo de Agua',
                        'calidad' => 'Calidad del Agua',
                        'eficiencia' => 'Eficiencia de Dispositivos',
                        'reciclaje' => 'Reciclaje de Agua',
                        'otro' => 'Otro'
                    ]),
                Forms\Components\DatePicker::make('MONIT_FECHA')
                    ->required()
                    ->label('Fecha'),
                Forms\Components\KeyValue::make('MONIT_METRICAS')
                    ->required()
                    ->label('Métricas')
                    ->keyLabel('Indicador')
                    ->valueLabel('Valor')
                    ->addActionLabel('Agregar Métrica'),
                Forms\Components\Textarea::make('MONIT_RESULTADOS')
                    ->required()
                    ->label('Resultados')
                    ->maxLength(65535),
                Forms\Components\Toggle::make('MONIT_USO_TIC')
                    ->required()
                    ->label('¿Usa TICs?')
                    ->helperText('¿Se utilizan Tecnologías de Información y Comunicación para el monitoreo?')
                    ->inline(false),
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
                Tables\Columns\TextColumn::make('empleado.EMP_NOMBRES')
                    ->label('Responsable')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('MONIT_TIPO')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'consumo' => 'Consumo de Agua',
                        'calidad' => 'Calidad del Agua',
                        'eficiencia' => 'Eficiencia de Dispositivos',
                        'reciclaje' => 'Reciclaje de Agua',
                        'otro' => 'Otro',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('MONIT_FECHA')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\IconColumn::make('MONIT_USO_TIC')
                    ->label('Usa TICs')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('CAMPUS_ID')
                    ->relationship('campus', 'CAMPUS_NOMBRES')
                    ->label('Campus'),
                Tables\Filters\SelectFilter::make('EMP_DNI')
                    ->relationship('empleado', 'EMP_NOMBRES')
                    ->label('Responsable'),
                Tables\Filters\SelectFilter::make('MONIT_TIPO')
                    ->label('Tipo de Monitoreo')
                    ->options([
                        'consumo' => 'Consumo de Agua',
                        'calidad' => 'Calidad del Agua',
                        'eficiencia' => 'Eficiencia de Dispositivos',
                        'reciclaje' => 'Reciclaje de Agua',
                        'otro' => 'Otro'
                    ]),
                Tables\Filters\Filter::make('MONIT_FECHA')
                    ->form([
                        Forms\Components\DatePicker::make('desde')
                            ->label('Desde'),
                        Forms\Components\DatePicker::make('hasta')
                            ->label('Hasta'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['desde'],
                                fn ($query) => $query->whereDate('MONIT_FECHA', '>=', $data['desde'])
                            )
                            ->when(
                                $data['hasta'],
                                fn ($query) => $query->whereDate('MONIT_FECHA', '<=', $data['hasta'])
                            );
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMonitoreoProgramas::route('/'),
            'create' => Pages\CreateMonitoreoPrograma::route('/create'),
            'view' => Pages\ViewMonitoreoPrograma::route('/{record}'),
            'edit' => Pages\EditMonitoreoPrograma::route('/{record}/edit'),
        ];
    }
}
