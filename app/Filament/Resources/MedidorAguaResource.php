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

class MedidorAguaResource extends Resource
{
    use HasRoleRestrictions;

    protected static ?string $model = MedidorAgua::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?string $navigationGroup = 'Gestión de Agua';
    protected static ?int $navigationSort = 2;
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
                Tables\Columns\TextColumn::make('MEDAG_ID')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('campus.CAMPUS_NOMBRES')
                    ->label('Campus')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('MEDAG_FECHAADQUISICION')
                    ->label('Fecha de Adquisición')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('CAMPUS_ID')
                    ->label('Campus')
                    ->relationship('campus', 'CAMPUS_NOMBRES'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
