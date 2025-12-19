<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RaceResource\Pages;
use App\Filament\Resources\RaceResource\RelationManagers\ResultsRelationManager; 
use App\Models\Race;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use App\Filament\Resources\RaceResource\RelationManagers\QualifyingResultsRelationManager;

class RaceResource extends Resource
{
    protected static ?string $model = Race::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationLabel = 'Races';
    protected static ?string $modelLabel = 'Race';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Select::make('track_id')
                            ->relationship('track', 'name')
                            ->label('Circuit')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('title')
                            ->label('Event Title')
                            ->placeholder('Ex: British Grand Prix')
                            ->maxLength(255),

                        Forms\Components\Select::make('season_id')
                            ->relationship('season', 'name')
                            ->required()
                            ->default(fn() => \App\Models\Season::where('is_active', true)->first()?->id)
                            ->label('Season'),
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('round_number')
                            ->label('Round #')
                            ->numeric()
                            ->required()
                            ->default(1),

                        Forms\Components\TextInput::make('total_laps')
                            ->label('Total Laps')
                            ->numeric()
                            ->required(),

                        Forms\Components\DateTimePicker::make('race_date')
                            ->label('Date & Time')
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'scheduled' => 'Scheduled',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('scheduled')
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Columna para ver la temporada
                Tables\Columns\TextColumn::make('season.name')
                    ->label('Season')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                Tables\Columns\TextColumn::make('round_number')
                    ->label('Round')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Event')
                    ->searchable()
                    ->placeholder('No title'),

                Tables\Columns\TextColumn::make('track.name')
                    ->label('Circuit')
                    ->sortable(),

                Tables\Columns\TextColumn::make('race_date')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
            ])
            ->defaultSort('race_date', 'asc')
            
            // --- FILTRO DE TEMPORADA ---
            ->filters([
                Tables\Filters\SelectFilter::make('season_id')
                    ->relationship('season', 'name')
                    ->label('Filter by Season')
                    // Por defecto: Selecciona la temporada activa
                    ->default(fn() => \App\Models\Season::where('is_active', true)->first()?->id),
            ])
            // ---------------------------

            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            QualifyingResultsRelationManager::class,
            ResultsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRaces::route('/'),
            'create' => Pages\CreateRace::route('/create'),
            'edit' => Pages\EditRace::route('/{record}/edit'),
        ];
    }
}