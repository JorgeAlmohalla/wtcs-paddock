<?php

namespace App\Filament\Resources\RaceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\User;

class QualifyingResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'qualifyingResults'; // <--- OJO AQUÍ

    protected static ?string $title = 'Qualifying Results';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('driver', 'name')
                    ->searchable()->preload()->required()->reactive()
                    ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('team_id', User::find($state)?->team_id)),
                
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name')->required(),

                Forms\Components\TextInput::make('position')
                    ->label('Grid Pos')
                    ->numeric()->required(),

                Forms\Components\TextInput::make('best_time')
                    ->label('Time')
                    ->placeholder('1:09.355')
                    ->mask('9:99.999'),

                Forms\Components\Select::make('tyre_compound')
                    ->label('Tyre')
                    ->options([
                        'soft' => 'Soft 🔴',
                        'medium' => 'Medium 🟡',
                        'hard' => 'Hard ⚪',
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('position')
            ->columns([
                Tables\Columns\TextColumn::make('position')->label('Pos')->sortable()->weight('bold'),
                Tables\Columns\TextColumn::make('driver.name')->label('Driver')->weight('bold'),
                Tables\Columns\TextColumn::make('team.name')->label('Team')->color('gray'),
                Tables\Columns\TextColumn::make('best_time')->label('Time'),
                Tables\Columns\TextColumn::make('tyre_compound')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'soft' => 'danger',
                        'medium' => 'warning',
                        'hard' => 'gray',
                        default => 'gray'
                    }),
            ])
            ->defaultSort('position', 'asc')
            ->headerActions([Tables\Actions\CreateAction::make()->label('Add Time')])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }
}