<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumoAguaResource\Pages;
use App\Filament\Resources\Traits\HasRoleRestrictions;
use App\Models\ConsumoAgua;
use App\Models\MedidorAgua;
use App\Models\UnidadMedidaAgua;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ConsumoAguaResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = ConsumoAgua::class;
    protected static ?string $navigationIcon = 'heroicon-o-cloud';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?string $navigationLabel = 'Consumo de Agua';
    protected static ?string $pluralModelLabel = 'Consumos de Agua';
    protected static ?string $modelLabel = 'Consumo de Agua';
    protected static ?int $navigationSort = 2;


    protected static function getAllowedRoles(): array
    {
        return ['TAG', 'ADM'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Consumo')
                    ->description('Registre los detalles del consumo de agua')
                    ->icon('heroicon-o-beaker')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('MEDAG_ID')
                            ->relationship('medidorAgua', 'MEDAG_ID')
                            ->required()
                            ->label('Medidor')
                            ->searchable()
                            ->prefixIcon('heroicon-m-calculator'),
                        Forms\Components\Select::make('MEDIDADAG_COD')
                            ->relationship('unidadMedida', 'MEDIDADAG_COD')
                            ->required()
                            ->label('Unidad de Medida')
                            ->searchable()
                            ->prefixIcon('heroicon-m-scale'),
                        Forms\Components\TextInput::make('CONSAG_TOTAL')
                            ->required()
                            ->numeric()
                            ->label('Total Consumido')
                            ->minValue(0)
                            ->prefixIcon('heroicon-m-variable'),
                        Forms\Components\DatePicker::make('CONSENE_FECHAPAGO')
                            ->required()
                            ->label('Fecha de Pago')
                            ->maxDate(now())
                            ->prefixIcon('heroicon-m-calendar'),
                    ]),
                Forms\Components\Section::make('Información de Costos')
                    ->description('Registre los detalles del costo del agua')
                    ->icon('heroicon-o-currency-dollar')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('costos.COSTAG_VALORAGREGADO')
                                    ->label('Valor Agregado')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->prefix('$')
                                    ->prefixIcon('heroicon-m-banknotes'),
                                Forms\Components\TextInput::make('costos.COSTENE_SUBTOTAL')
                                    ->label('Subtotal')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->prefix('$')
                                    ->prefixIcon('heroicon-m-calculator'),
                                Forms\Components\TextInput::make('costos.COSTOAG_IVA')
                                    ->label('IVA')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->prefix('$')
                                    ->prefixIcon('heroicon-m-receipt-percent'),
                                Forms\Components\TextInput::make('costos.COSTOAG_TOTAL')
                                    ->label('Total')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->prefix('$')
                                    ->prefixIcon('heroicon-m-currency-dollar'),
                            ]),
                    ]),
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
                Tables\Columns\TextColumn::make('medidorAgua.MEDAG_NOMBRE')
                    ->label('Medidor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CONSAG_FECHA')
                    ->label('Fecha')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('CONSAG_CONSUMO')
                    ->label('Consumo')
                    ->numeric(2)
                    ->suffix(' m³')
                    ->sortable()
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function ($record) {
                        $tratamientos = $record->tratamientosAgua;
                        $totalTratado = $tratamientos->sum('TRAGUA_TOTAL');
                        $porcentajeTratado = $record->CONSAG_TOTAL > 0 ? round(($totalTratado / $record->CONSAG_TOTAL) * 100, 2) : 0;
                        
                        $html = "
                            <div class='space-y-4'>
                                <div class='text-xl font-bold'>Detalles del Consumo de Agua</div>
                                
                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Campus</div>
                                        <div>{$record->medidorAgua->campus->CAMPUS_NOMBRES}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Medidor</div>
                                        <div>{$record->medidorAgua->MEDAG_NOMBRE}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Consumo Total</div>
                                        <div>{$record->CONSAG_TOTAL} m³</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Fecha</div>
                                        <div>{$record->CONSAG_FECHA->format('d/m/Y')}</div>
                                    </div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold mb-2'>Tratamientos de Agua</div>
                                    <div class='mb-2'>Total tratado: {$totalTratado} m³ ({$porcentajeTratado}% del consumo)</div>";
                        
                        if ($tratamientos->count() > 0) {
                            $html .= "<div class='space-y-2'>";
                            foreach ($tratamientos as $tratamiento) {
                                $totalReciclado = $tratamiento->aguasRecicladas()->sum('AGUAREC_CANTIDAD');
                                $html .= "
                                    <div class='bg-gray-50 p-2 rounded'>
                                        <div>Tipo: {$tratamiento->tipoTratamiento->TIPOTRA_NOMBRES}</div>
                                        <div>Cantidad: {$tratamiento->TRAGUA_TOTAL} m³ ({$tratamiento->TRAGUA_PORCENTAJE_TRATADO}%)</div>
                                        <div>Reciclado: {$totalReciclado} m³ ({$tratamiento->TRAGUA_PORCENTAJE_RECICLADO}%)</div>
                                        <div>Estado: {$tratamiento->TRAGUA_ESTADO_PROGRAMA}</div>
                                    </div>";
                            }
                            $html .= "</div>";
                        } else {
                            $html .= "<div class='text-gray-500'>No hay tratamientos registrados</div>";
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
            ->filters([ /*...*/ ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ])
            ->emptyStateActions([Tables\Actions\CreateAction::make()])
            ->defaultSort('costos.COSTOAG_TOTAL', 'desc')
            ->poll('60s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Información del Medidor')
                    ->description('Detalles del medidor y ubicación')
                    ->schema([
                        TextEntry::make('medidorAgua.MEDAG_ID')
                            ->label('Código del Medidor'),
                        TextEntry::make('medidorAgua.MEDAG_FECHAADQUISICION')
                            ->label('Fecha de Adquisición')
                            ->date('d/m/Y'),
                        TextEntry::make('medidorAgua.campus.CAMPUS_NOMBRES')
                            ->label('Campus'),
                        TextEntry::make('medidorAgua.campus.CAMPUS_CALLEPRINCIPAL')
                            ->label('Calle Principal'),
                    ])->columns(2),
                Section::make('Información del Consumo')
                    ->description('Detalles del consumo de agua registrado')
                    ->schema([
                        TextEntry::make('CONSAG_TOTAL')
                            ->label('Total Consumido')
                            ->numeric(
                                decimalPlaces: 2,
                                decimalSeparator: ',',
                                thousandsSeparator: '.'
                            )
                            ->suffix(' m³'),
                        TextEntry::make('unidadMedida.MEDIDAAGU_NOMBRE')
                            ->label('Unidad de Medida'),
                        TextEntry::make('CONSENE_FECHAPAGO')
                            ->label('Fecha de Pago')
                            ->date('d/m/Y'),
                    ])->columns(2),
                Section::make('Información de Costos')
                    ->description('Detalles del costo del agua')
                    ->schema([
                        TextEntry::make('costos.COSTAG_VALORAGREGADO')
                            ->label('Valor Agregado')
                            ->numeric(
                                decimalPlaces: 2,
                                decimalSeparator: ',',
                                thousandsSeparator: '.'
                            )
                            ->prefix('$ '),
                        TextEntry::make('costos.COSTENE_SUBTOTAL')
                            ->label('Subtotal')
                            ->numeric(
                                decimalPlaces: 2,
                                decimalSeparator: ',',
                                thousandsSeparator: '.'
                            )
                            ->prefix('$ '),
                        TextEntry::make('costos.COSTOAG_IVA')
                            ->label('IVA (12%)')
                            ->numeric(
                                decimalPlaces: 2,
                                decimalSeparator: ',',
                                thousandsSeparator: '.'
                            )
                            ->prefix('$ '),
                        TextEntry::make('costos.COSTOAG_TOTAL')
                            ->label('Total')
                            ->numeric(
                                decimalPlaces: 2,
                                decimalSeparator: ',',
                                thousandsSeparator: '.'
                            )
                            ->prefix('$ ')
                            ->weight('bold')
                            ->color('success'),
                    ])->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [/*...*/];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListConsumosAgua::route('/'),
            'create' => Pages\CreateConsumoAgua::route('/create'),
            'edit' => Pages\EditConsumoAgua::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['medidorAgua.campus', 'unidadMedida', 'costos']);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'success';
    }
}
