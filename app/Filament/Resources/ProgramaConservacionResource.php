<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramaConservacionResource\Pages;
use App\Models\ProgramaConservacion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

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
                    ->required(),
                Forms\Components\TextInput::make('PROGCONS_NOMBRE')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Textarea::make('PROGCONS_DESCRIPCION')
                    ->maxLength(65535),
                Forms\Components\Select::make('PROGCONS_ESTADO')
                    ->options([
                        'planificacion' => 'Planificación',
                        'implementacion' => 'Implementación',
                        'evaluacion' => 'Evaluación',
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('PROGCONS_FECHAINICIO')
                    ->required(),
                Forms\Components\DatePicker::make('PROGCONS_FECHAFIN'),
                Forms\Components\TextInput::make('PROGCONS_AVANCE')
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
                    ->searchable(),
                Tables\Columns\TextColumn::make('PROGCONS_NOMBRE')
                    ->label('Nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('PROGCONS_ESTADO')
                    ->label('Estado'),
                Tables\Columns\TextColumn::make('PROGCONS_AVANCE')
                    ->label('Avance')
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('PROGCONS_FECHAINICIO')
                    ->label('Fecha Inicio')
                    ->date(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListProgramaConservacions::route('/'),
            'create' => Pages\CreateProgramaConservacion::route('/create'),
            'edit' => Pages\EditProgramaConservacion::route('/{record}/edit'),
        ];
    }
}
