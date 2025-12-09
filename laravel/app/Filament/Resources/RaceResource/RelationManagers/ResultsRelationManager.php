<?php

namespace App\Filament\Resources\RaceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    protected static ?string $title = 'Race Results'; // Título de la sección

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('driver', 'name') // Busca pilotos
                    ->label('Driver')
                    ->searchable()
                    ->preload()
                    ->required()
                    // Truco PRO: Al elegir piloto, autoselecciona su equipo actual
                    ->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                        $set('team_id', \App\Models\User::find($state)?->team_id)
                    ),

                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')
                    ->label('Team (at that moment)')
                    ->required(),

                Forms\Components\TextInput::make('position')
                    ->label('Finish Position')
                    ->numeric()
                    ->required(),
                
                Forms\Components\TextInput::make('grid_position')
                    ->label('Qualy Position')
                    ->numeric(),

                Forms\Components\Toggle::make('fastest_lap')
                    ->label('Fastest Lap (+1 pt)')
                    ->onColor('success'),
                
                Forms\Components\Toggle::make('dnf')
                    ->label('DNF (Did Not Finish)')
                    ->onColor('danger'),
                
                Forms\Components\TextInput::make('penalty_seconds')
                    ->label('Penalty (sec)')
                    ->numeric()
                    ->default(0),
                
                Tables\Columns\TextColumn::make('points')
                    ->label('PTS')
                    ->weight('extra-bold') // Que se vea bien fuerte
                    ->color('primary')
                    ->suffix(' pts'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')
                    ->label('Pos')
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->searchable(),

                Tables\Columns\TextColumn::make('team.name')
                    ->label('Team')
                    ->color('gray'),

                Tables\Columns\IconColumn::make('fastest_lap')
                    ->boolean()
                    ->label('FL'), // Vuelta rápida

                Tables\Columns\IconColumn::make('dnf')
                    ->boolean()
                    ->label('DNF')
                    ->trueColor('danger'),
            ])
            ->defaultSort('position', 'asc')
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Result'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}