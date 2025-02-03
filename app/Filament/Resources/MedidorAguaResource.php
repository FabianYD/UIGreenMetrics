<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedidorAguaResource\Pages;
use App\Filament\Resources\Traits\HasRoleRestrictions;
use App\Models\MedidorAgua;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class MedidorAguaResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = MedidorAgua::class;
    protected static ?string $navigationIcon = 'heroicon-o-wallet';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Medidores de Agua';
    protected static ?string $pluralModelLabel = 'medidores de agua';
    protected static ?string $modelLabel = 'medidor de agua';

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
                        Forms\Components\TextInput::make('MEDAG_ID')
                            ->label('ID del Medidor')
                            ->required()
                            ->maxLength(6)
                            ->unique(ignoreRecord: true),
                        Forms\Components\Select::make('CAMPUS_ID')
                            ->label('Campus')
                            ->relationship('campus', 'CAMPUS_NOMBRES')
                            ->required(),
                        Forms\Components\DatePicker::make('MEDAG_FECHAADQUISICION')
                            ->label('Fecha de Adquisición')
                            ->required()
                            ->maxDate(now()),
                    ])
                    ->columns(2)
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
                Tables\Columns\TextColumn::make('MEDAG_NOMBRE')
                    ->label('Medidor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MEDAG_UBICACION')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('MEDAG_ESTADO')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'activo' => 'success',
                        'mantenimiento' => 'warning',
                        'inactivo' => 'danger',
                        default => 'secondary',
                    })
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalContent(function ($record) {
                        $consumos = $record->consumosAgua()->orderBy('CONSAG_FECHA', 'desc')->take(5)->get();
                        $totalConsumo = $record->consumosAgua()->sum('CONSAG_TOTAL');
                        
                        $html = "
                            <div class='space-y-4'>
                                <div class='text-xl font-bold'>Detalles del Medidor de Agua</div>
                                
                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Campus</div>
                                        <div>{$record->campus->CAMPUS_NOMBRES}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Nombre del Medidor</div>
                                        <div>{$record->MEDAG_NOMBRE}</div>
                                    </div>
                                </div>

                                <div class='grid grid-cols-2 gap-4'>
                                    <div>
                                        <div class='font-semibold'>Ubicación</div>
                                        <div>{$record->MEDAG_UBICACION}</div>
                                    </div>
                                    <div>
                                        <div class='font-semibold'>Estado</div>
                                        <div>{$record->MEDAG_ESTADO}</div>
                                    </div>
                                </div>

                                <div class='border-t pt-4 mt-4'>
                                    <div class='font-semibold mb-2'>Consumos Recientes</div>
                                    <div class='mb-2'>Consumo total registrado: {$totalConsumo} m³</div>";
                        
                        if ($consumos->count() > 0) {
                            $html .= "<div class='space-y-2'>";
                            foreach ($consumos as $consumo) {
                                $tratamientos = $consumo->tratamientosAgua;
                                $totalTratado = $tratamientos->sum('TRAGUA_TOTAL');
                                $porcentajeTratado = $consumo->CONSAG_TOTAL > 0 ? round(($totalTratado / $consumo->CONSAG_TOTAL) * 100, 2) : 0;
                                
                                $html .= "
                                    <div class='bg-gray-50 p-2 rounded'>
                                        <div>Fecha: {$consumo->CONSAG_FECHA->format('d/m/Y')}</div>
                                        <div>Consumo: {$consumo->CONSAG_TOTAL} m³</div>
                                        <div>Agua Tratada: {$totalTratado} m³ ({$porcentajeTratado}%)</div>
                                    </div>";
                            }
                            $html .= "</div>";
                        } else {
                            $html .= "<div class='text-gray-500'>No hay consumos registrados</div>";
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
                Tables\Filters\SelectFilter::make('CAMPUS_ID')
                    ->label('Campus')
                    ->relationship('campus', 'CAMPUS_NOMBRES'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedidoresAgua::route('/'),
            'create' => Pages\CreateMedidorAgua::route('/create'),
            'edit' => Pages\EditMedidorAgua::route('/{record}/edit'),
        ];
    }
}
