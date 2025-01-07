<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EnergyResource\Pages;
use App\Models\Energy;
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

class EnergyResource extends Resource
{
    protected static ?string $model = Energy::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Gestión Ambiental';
    protected static ?string $navigationLabel = 'Consumo de Energía';
    protected static ?int $navigationSort = 1;
    protected static ?string $modelLabel = 'Energía';
    protected static ?string $pluralModelLabel = 'Energías';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información de Consumo Energético')
                    ->description('Registre los detalles del consumo de energía')
                    ->icon('heroicon-o-light-bulb')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('tipo_energia')
                                ->label('Tipo de Energía')
                                ->options([
                                    'electrica' => 'Eléctrica',
                                    'solar' => 'Solar',
                                    'eolica' => 'Eólica',
                                    'gas' => 'Gas Natural',
                                ])
                                ->required()
                                ->searchable()
                                ->native(false)
                                ->prefixIcon('heroicon-m-bolt')
                                ->helperText('Seleccione el tipo de energía consumida'),

                            Forms\Components\TextInput::make('consumo')
                                ->label('Consumo')
                                ->numeric()
                                ->required()
                                ->step(0.01)
                                ->prefix('⚡')
                                ->helperText('Ingrese la cantidad consumida'),

                            Forms\Components\DatePicker::make('fecha_registro')
                                ->label('Fecha de Registro')
                                ->required()
                                ->native(false)
                                ->displayFormat('d/m/Y')
                                ->prefixIcon('heroicon-m-calendar'),

                            Forms\Components\TextInput::make('unidad_medida')
                                ->label('Unidad de Medida')
                                ->required()
                                ->placeholder('kWh, m³, etc.')
                                ->prefixIcon('heroicon-m-scale'),

                            Forms\Components\TextInput::make('costo')
                                ->label('Costo')
                                ->numeric()
                                ->prefix('$')
                                ->required()
                                ->step(0.01)
                                ->prefixIcon('heroicon-m-currency-dollar'),

                            Forms\Components\TextInput::make('ubicacion')
                                ->label('Ubicación')
                                ->prefixIcon('heroicon-m-map-pin'),
                        ]),

                        Forms\Components\Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull()
                            ->placeholder('Agregue detalles adicionales sobre el consumo energético'),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tipo_energia')
                    ->label('Tipo de Energía')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'electrica' => 'warning',
                        'solar' => 'success',
                        'eolica' => 'info',
                        'gas' => 'danger',
                    })
                    ->icon('heroicon-m-bolt')
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('consumo')
                    ->label('Consumo')
                    ->sortable()
                    ->numeric(2)
                    ->suffix(fn ($record) => ' ' . $record->unidad_medida)
                    ->alignRight(),

                Tables\Columns\TextColumn::make('fecha_registro')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable()
                    ->icon('heroicon-m-calendar'),

                Tables\Columns\TextColumn::make('costo')
                    ->label('Costo')
                    ->money('USD')
                    ->sortable()
                    ->alignRight()
                    ->color('success'),

                Tables\Columns\TextColumn::make('ubicacion')
                    ->label('Ubicación')
                    ->icon('heroicon-m-map-pin')
                    ->searchable(),
            ])
            ->defaultSort('fecha_registro', 'desc')
            ->filters([
                SelectFilter::make('tipo_energia')
                    ->label('Tipo de Energía')
                    ->options([
                        'electrica' => 'Eléctrica',
                        'solar' => 'Solar',
                        'eolica' => 'Eólica',
                        'gas' => 'Gas Natural',
                    ])
                    ->indicator('Tipo'),

                Filter::make('fecha_registro')
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
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_registro', '>=', $date),
                            )
                            ->when(
                                $data['fecha_hasta'],
                                fn (Builder $query, $date): Builder => $query->whereDate('fecha_registro', '<=', $date),
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
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->color('danger'),
                Tables\Actions\Action::make('download_report')
                    ->label('Descargar Reporte')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->action(function ($record) {
                        $reportService = app(ReportService::class);
                        $content = $reportService->generateEnergyReport($record);
                        
                        return response()->streamDownload(
                            function () use ($content) {
                                echo $content;
                            },
                            'reporte-energia-' . $record->id . '.docx',
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
            ->emptyStateIcon('heroicon-o-bolt')
            ->emptyStateHeading('No hay registros de energía')
            ->emptyStateDescription('Comienza agregando un nuevo registro de consumo energético')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
                    ->label('Nuevo Registro')
                    ->icon('heroicon-m-plus'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEnergies::route('/'),
            'create' => Pages\CreateEnergy::route('/create'),
            'edit' => Pages\EditEnergy::route('/{record}/edit'),
        ];
    }
}
