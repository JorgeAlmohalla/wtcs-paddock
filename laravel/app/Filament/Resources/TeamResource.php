<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    
    // --- TRADUCCIÓN MENÚ ---
    protected static ?string $navigationLabel = 'Teams'; // Antes "Equipos"
    protected static ?string $modelLabel = 'Team';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Team Name') // Inglés
                    ->required()
                    ->maxLength(255),
                
                Forms\Components\TextInput::make('short_name')
                    ->label('Abbreviation (TAG)')
                    ->required()
                    ->maxLength(3)
                    ->placeholder('Ex: RBR'),

                Forms\Components\Select::make('type')
                    ->label('Entry Type')
                    ->options([
                        'works' => 'Works (Official)',
                        'privateer' => 'Privateer (Independent)',
                    ])
                    ->required()
                    ->default('privateer'),

                Forms\Components\TextInput::make('car_brand')
                    ->label('Car Manufacturer')
                    ->placeholder('Ex: Porsche'),

                Forms\Components\ColorPicker::make('primary_color')
                    ->label('Primary Color'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('short_name')
                    ->label('Tag'),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'works' => 'success',
                        'privateer' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)), // Pone la primera letra mayúscula

                Tables\Columns\ColorColumn::make('primary_color')
                    ->label('Color'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}