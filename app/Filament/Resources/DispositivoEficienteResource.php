<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DispositivoEficienteResource\Pages;
use App\Models\DispositivoEficiente;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('facultad.FACU_NOMBRE')
                    ->label('Facultad')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DISPEF_TIPO')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'grifo_sensor' => 'Grifo con Sensor',
                        'inodoro_dual' => 'Inodoro de Doble Descarga',
                        'urinario_seco' => 'Urinario Seco',
                        'regadera_ahorradora' => 'Regadera Ahorradora',
                        'medidor_inteligente' => 'Medidor Inteligente',
                        'otro' => 'Otro',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('DISPEF_UBICACION')
                    ->label('Ubicación')
                    ->searchable(),
                Tables\Columns\TextColumn::make('DISPEF_EFICIENCIA')
                    ->label('Eficiencia')
                    ->numeric(2)
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('DISPEF_FECHAINSTALACION')
                    ->label('Instalación')
                    ->date()
                    ->sortable(),
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
            'index' => Pages\ListDispositivoEficientes::route('/'),
            'create' => Pages\CreateDispositivoEficiente::route('/create'),
            'edit' => Pages\EditDispositivoEficiente::route('/{record}/edit'),
        ];
    }
}
