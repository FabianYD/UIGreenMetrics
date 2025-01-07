<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WaterResource\Pages;
use App\Models\Water;
use App\Services\ReportService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;

class WaterResource extends Resource
{
    protected static ?string $model = Water::class;
    protected static ?string $navigationIcon = 'heroicon-o-cloud';
    protected static ?string $navigationGroup = 'Gestión Ambiental';
    protected static ?string $navigationLabel = 'Consumo de Agua';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Agua';
    protected static ?string $pluralModelLabel = 'Registros de Agua';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de Consumo de Agua')
                    ->description('Registre los detalles del consumo de agua')
                    ->icon('heroicon-o-beaker')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('medidor_id')
                                ->label('ID de Medidor')
                                ->required()
                                ->maxLength(50)
                                ->prefixIcon('heroicon-m-identification'),

                            Forms\Components\Select::make('tipo_consumo')
                                ->label('Tipo de Consumo')
                                ->options([
                                    'potable' => 'Agua Potable',
                                    'reciclada' => 'Agua Reciclada',
                                    'tratada' => 'Agua Tratada',
                                    'lluvia' => 'Agua de Lluvia',
                                ])
                                ->required()
                                ->native(false)
                                ->prefixIcon('heroicon-m-beaker'),

                            Forms\Components\TextInput::make('consumo_total')
                                ->label('Consumo Total')
                                ->numeric()
                                ->required()
                                ->step(0.01)
                                ->prefixIcon('heroicon-m-beaker')
                                ->helperText('Ingrese la cantidad consumida en metros cúbicos'),

                            Forms\Components\DatePicker::make('fecha_pago')
                                ->label('Fecha de Pago')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->prefixIcon('heroicon-m-calendar'),

                            Forms\Components\TextInput::make('ubicacion')
                                ->label('Ubicación')
                                ->prefixIcon('heroicon-m-map-pin'),

                            Forms\Components\Textarea::make('descripcion')
                                ->label('Descripción')
                                ->rows(3)
                                ->columnSpanFull()
                                ->placeholder('Agregue detalles adicionales sobre el consumo de agua'),
                        ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medidor_id')
                    ->label('ID Medidor')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-m-identification')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('tipo_consumo')
                    ->label('Tipo')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'potable' => 'info',
                        'reciclada' => 'success',
                        'tratada' => 'warning',
                        'lluvia' => 'primary',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('consumo_total')
                    ->label('Consumo Total')
                    ->sortable()
                    ->numeric(2)
                    ->suffix(' m³')
                    ->alignRight()
                    ->color('info'),

                Tables\Columns\TextColumn::make('fecha_pago')
                    ->label('Fecha de Pago')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),

                Tables\Columns\TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->searchable()
                    ->icon('heroicon-m-map-pin'),
            ])
            ->defaultSort('fecha_pago', 'desc')
            ->filters([
                SelectFilter::make('tipo_consumo')
                    ->label('Tipo de Consumo')
                    ->options([
                        'potable' => 'Agua Potable',
                        'reciclada' => 'Agua Reciclada',
                        'tratada' => 'Agua Tratada',
                        'lluvia' => 'Agua de Lluvia',
                    ])
                    ->indicator('Tipo'),

                Filter::make('fecha_pago')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_pago', '>=', $date),
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_pago', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['fecha_desde'] ?? null) {
                            $indicators[] = 'Desde ' . date('d/m/Y', strtotime($data['fecha_desde']));
                        }
                        if ($data['fecha_hasta'] ?? null) {
                            $indicators[] = 'Hasta ' . date('d/m/Y', strtotime($data['fecha_hasta']));
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->color('info'),
                Tables\Actions\DeleteAction::make()
                    ->color('danger'),
                Tables\Actions\Action::make('download_report')
                    ->label('Descargar Reporte')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $reportService = app(ReportService::class);
                        $content = $reportService->generateWaterReport($record);
                        
                        return response()->streamDownload(
                            function () use ($content) {
                                echo $content;
                            },
                            'reporte-agua-' . $record->id . '.docx',
                            [
                                'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            ]
                        );
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateIcon('heroicon-o-beaker')
            ->emptyStateHeading('No hay registros de consumo de agua')
            ->emptyStateDescription('Comienza agregando un nuevo registro de consumo de agua')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Registro')
                    ->icon('heroicon-m-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWaters::route('/'),
            'create' => Pages\CreateWater::route('/create'),
            'edit' => Pages\EditWater::route('/{record}/edit'),
        ];
    }
}
