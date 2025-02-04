<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsumoEnergiaResource\Pages;
use App\Filament\Resources\Traits\HasRoleRestrictions;
use App\Models\ConsumoEnergia;
use App\Models\MedidorElectrico;
use App\Models\TipoEnergia;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Grid;
use Illuminate\Database\Eloquent\Builder;

class ConsumoEnergiaResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = ConsumoEnergia::class;
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?string $navigationGroup = 'Gestión de Energía';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Consumo de Energía';
    protected static ?string $pluralModelLabel = 'consumos de energía';
    protected static ?string $modelLabel = 'consumo de energía';

    protected static function getAllowedRoles(): array
    {
        return ['TEN', 'ADM'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Consumo')
                    ->description('Ingrese los detalles del consumo de energía')
                    ->icon('heroicon-o-bolt')
                    ->schema([
                        Forms\Components\Select::make('IDMEDIDOR2')
                            ->relationship('medidorElectrico', 'IDMEDIDOR2') // Cambié 'medidor' a 'medidorElectrico'
                            ->required()
                            ->label('Medidor')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('IDMEDIDOR2')
                                    ->required()
                                    ->label('Código del Medidor'),
                                Forms\Components\Select::make('CAMPUS_ID')
                                    ->relationship('campus', 'CAMPUS_NOMBRES')
                                    ->required()
                                    ->label('Campus'),
                            ])
                            ->prefixIcon('heroicon-m-calculator'),
                        Forms\Components\Select::make('TIPOENE_ID')
                            ->relationship('tipoEnergia', 'TIPOENE_NOMBRES')
                            ->required()
                            ->label('Tipo de Energía')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-bolt'),
                        Forms\Components\Select::make('MEDENE_COD')
                            ->relationship('unidadMedidaEnergia', 'MEDENE_NOMBRE') // Cambié 'unidadMedida' a 'unidadMedidaEnergia'
                            ->required()
                            ->label('Unidad de Medida')
                            ->searchable()
                            ->preload()
                            ->prefixIcon('heroicon-m-scale'),
                        Forms\Components\TextInput::make('CONSENE_TOTAL')
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
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('medidorElectrico.IDMEDIDOR2') // Cambié 'medidor' a 'medidorElectrico'
                    ->label('Código del Medidor')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('medidorElectrico.campus.CAMPUS_NOMBRES') // Cambié 'medidor' a 'medidorElectrico'
                    ->label('Campus')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipoEnergia.TIPOENE_NOMBRES')
                    ->label('Tipo de Energía')
                    ->searchable(),
                Tables\Columns\TextColumn::make('CONSENE_TOTAL')
                    ->label('Total Consumido')
                    ->suffix(' kW/h')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.'
                    )
                    ->sortable(),
                Tables\Columns\TextColumn::make('costos.COSTENE_TOTAL')
                    ->label('Valor Total')
                    ->prefix('$ ')
                    ->numeric(
                        decimalPlaces: 2,
                        decimalSeparator: ',',
                        thousandsSeparator: '.'
                    )
                    ->sortable()
                    ->color('success'),
            ])
            ->filters([
                //
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('CONSENE_FECHAPAGO', 'desc')
            ->poll('60s');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Section::make('Medidor y Ubicación')
                                    ->description('Información del medidor y su ubicación')
                                    ->icon('heroicon-o-map-pin')
                                    ->collapsible()
                                    ->schema([
                                        TextEntry::make('medidorElectrico.IDMEDIDOR2') // Cambié 'medidor' a 'medidorElectrico'
                                            ->label('Código del Medidor')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->weight('bold'),
                                        TextEntry::make('medidorElectrico.campus.CAMPUS_NOMBRES') // Cambié 'medidor' a 'medidorElectrico'
                                            ->label('Campus')
                                            ->size(TextEntry\TextEntrySize::Large),
                                        TextEntry::make('medidorElectrico.MEDAG_FECHAADQUISICION') // Cambié 'medidor' a 'medidorElectrico'
                                            ->label('Fecha de Adquisición')
                                            ->date('d/m/Y'),
                                        TextEntry::make('medidorElectrico.campus.CAMPUS_CALLEPRINCIPAL') // Cambié 'medidor' a 'medidorElectrico'
                                            ->label('Dirección')
                                            ->size(TextEntry\TextEntrySize::Large),
                                    ])->columns(1),

                                Section::make('Detalles del Consumo')
                                    ->description('Información sobre el consumo registrado')
                                    ->icon('heroicon-o-bolt')
                                    ->collapsible()
                                    ->schema([
                                        TextEntry::make('tipoEnergia.TIPOENE_NOMBRES')
                                            ->label('Tipo de Energía')
                                            ->size(TextEntry\TextEntrySize::Large),
                                        TextEntry::make('CONSENE_TOTAL')
                                            ->label('Total Consumido')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->numeric(
                                                decimalPlaces: 2,
                                                decimalSeparator: ',',
                                                thousandsSeparator: '.'
                                            )
                                            ->suffix(' kW/h'),
                                        TextEntry::make('CONSENE_FECHAPAGO')
                                            ->label('Fecha de Pago')
                                            ->date('d/m/Y'),
                                    ])->columns(1),
                            ]),

                        Section::make('Información de Costos')
                            ->description('Detalles de los costos asociados')
                            ->icon('heroicon-o-currency-dollar')
                            ->collapsible()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('costos.COSTENE_VALORCONS')
                                            ->label('Valor de Consumo')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->numeric(
                                                decimalPlaces: 2,
                                                decimalSeparator: ',',
                                                thousandsSeparator: '.'
                                            )
                                            ->prefix('$ '),
                                        TextEntry::make('costos.COSTENE_SUBSIDIO')
                                            ->label('Subsidio')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->numeric(
                                                decimalPlaces: 2,
                                                decimalSeparator: ',',
                                                thousandsSeparator: '.'
                                            )
                                            ->prefix('$ '),
                                        TextEntry::make('costos.COSTENE_SUBTOTAL')
                                            ->label('Subtotal')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->numeric(
                                                decimalPlaces: 2,
                                                decimalSeparator: ',',
                                                thousandsSeparator: '.'
                                            )
                                            ->prefix('$ '),
                                        TextEntry::make('costos.COSTENE_SUBTOTAL_ALUM_PUBLIC')
                                            ->label('Alumbrado Público')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->numeric(
                                                decimalPlaces: 2,
                                                decimalSeparator: ',',
                                                thousandsSeparator: '.'
                                            )
                                            ->prefix('$ '),
                                        TextEntry::make('costos.COSTENE_BASEIVA')
                                            ->label('Base IVA')
                                            ->size(TextEntry\TextEntrySize::Large)
                                            ->numeric(
                                                decimalPlaces: 2,
                                                decimalSeparator: ',',
                                                thousandsSeparator: '.'
                                            )
                                            ->prefix('$ '),
                                    ]),

                                TextEntry::make('costos.COSTENE_TOTAL')
                                    ->label('TOTAL A PAGAR')
                                    ->size(TextEntry\TextEntrySize::Large)
                                    ->numeric(
                                        decimalPlaces: 2,
                                        decimalSeparator: ',',
                                        thousandsSeparator: '.'
                                    )
                                    ->prefix('$ ')
                                    ->weight('bold')
                                    ->color('success')
                                    ->alignCenter(),
                            ]),
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
            'index' => Pages\ListConsumosEnergia::route('/'),
            'create' => Pages\CreateConsumoEnergia::route('/create'),
            'edit' => Pages\EditConsumoEnergia::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['medidorElectrico.campus', 'tipoEnergia', 'unidadMedidaEnergia', 'costos']);
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
