<?php

namespace App\Filament\Resources\RaceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\RaceResult;
use App\Models\User;

class ResultsRelationManager extends RelationManager
{
    protected static string $relationship = 'results';

    protected static ?string $title = 'Race Results';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                // GRUPO 1: PILOTO, NÚMERO Y EQUIPO
                Forms\Components\Group::make()->schema([
                    
                    // Piloto (Rellena equipo y número al cambiar)
                    Forms\Components\Select::make('user_id')
                        ->relationship('driver', 'name')
                        ->label('Driver')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                            $user = \App\Models\User::find($state);
                            if ($user) {
                                $set('team_id', $user->team_id);
                                $set('driver_number', $user->driver_number);
                            }
                        })
                        ->columnSpan(4), // Ancho grande

                    // Número (Dorsal)
                    Forms\Components\TextInput::make('driver_number')
                        ->label('#')
                        ->numeric()
                        ->columnSpan(2), // Ancho pequeño

                    // Equipo
                    Forms\Components\Select::make('team_id')
                        ->relationship('team', 'name')
                        ->label('Team')
                        ->required()
                        ->columnSpanFull(), // Ocupa toda la fila de abajo

                ])->columns(6)->columnSpanFull(),

                // GRUPO 2: POSICIONES
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('grid_position')->label('Grid Start')->numeric(),
                    Forms\Components\TextInput::make('position')->label('Final Pos')->numeric()->required(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'finished' => 'Finished',
                            'dnf' => 'DNF',
                            'dns' => 'DNS',
                            'dsq' => 'DSQ',
                            '+1 lap' => '+1 Lap',
                            '+2 laps' => '+2 Laps',
                            '+3 laps' => '+3 Laps'
                        ])
                        ->default('finished')->required(),
                ])->columns(3),

                // GRUPO 3: TIEMPOS
                Forms\Components\Group::make()->schema([
                    Forms\Components\TextInput::make('race_time')->label('Total Time')->mask('99:99.999'),
                    Forms\Components\TextInput::make('laps_completed')->label('Laps')->numeric(),
                    Forms\Components\TextInput::make('penalty_seconds')->label('Penalty')->numeric()->suffix('s')->default(0),
                ])->columns(3),

                // GRUPO 4: VUELTA RÁPIDA
                Forms\Components\Section::make('Fastest Lap Data')
                    ->schema([
                        Forms\Components\Toggle::make('fastest_lap')
                            ->label('Is Fastest Lap?')
                            ->onColor('purple')
                            ->reactive(),
                        
                        Forms\Components\TextInput::make('fastest_lap_time')
                            ->label('Lap Time')
                            ->placeholder('1:32.450')
                            ->mask('9:99.999')
                            ->hidden(fn (Forms\Get $get) => !$get('fastest_lap')),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('position')
            ->columns([
                // 1. GRID
                Tables\Columns\TextColumn::make('grid_position')
                    ->label('Grid')
                    ->alignCenter()
                    ->color('gray')
                    ->sortable(),

                // 2. POSICIÓN
                Tables\Columns\TextColumn::make('position')
                    ->label('Pos')
                    ->sortable()
                    ->weight('bold')
                    ->alignCenter()
                    ->size(Tables\Columns\TextColumn\TextColumnSize::Large),
                
                // 3. PILOTO
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->weight('bold')
                    ->searchable(),

                // 4. EQUIPO
                Tables\Columns\TextColumn::make('team.name')
                    ->label('Team')
                    ->color('gray')
                    ->limit(20)
                    ->toggleable(),

                // 5. VUELTAS
                Tables\Columns\TextColumn::make('laps_completed')
                    ->label('Laps')
                     ->numeric()
                    ->default(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->total_laps)
                    ->alignCenter(),

                // 6. TIEMPO (Si tiene penalización sale aviso abajo)
                Tables\Columns\TextColumn::make('race_time')
                    ->label('Time')
                    ->description(fn ($record) => 
                        $record->penalty_seconds > 0 ? "+{$record->penalty_seconds}s pen" : null
                    )
                    ->color(fn ($record) => $record->penalty_seconds > 0 ? 'danger' : null),

                // 7. VUELTA RÁPIDA (Morado)
                Tables\Columns\TextColumn::make('fastest_lap_time')
                    ->label('Best Lap')
                    ->placeholder('-')
                    // Forzamos el color del TEXTO
                    ->color('purple') 
                    // Forzamos el peso de la fuente
                    ->weight('bold')
                    // Truco: Usar HTML para forzar estilo si lo anterior falla
                    ->formatStateUsing(fn ($state) => $state ? "<span style='color: #a855f7; font-weight: bold;'>{$state}</span>" : null)
                    ->html(), // Permitir HTML

                // 8. ESTADO (DNF, DNS, Finished...) - ¡RECUPERADO!
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'finished' => 'success', // Verde
                        'dnf', 'dns' => 'danger', // Rojo
                        'dsq' => 'gray', // Gris
                        default => 'warning', // Naranja (+1 lap)
                    })
                    ->formatStateUsing(fn (string $state) => strtoupper($state)),

                // 9. PUNTOS
                Tables\Columns\TextColumn::make('points')
                    ->label('PTS')
                    ->formatStateUsing(fn (string $state): string => (int)$state)
                    ->weight('black')
                    ->color('primary')
                    ->alignRight(),
            ])
            ->defaultSort('position', 'asc')
            ->headerActions([
                // --- BOTÓN IMPORTAR CSV ---
                Tables\Actions\Action::make('importCsv')
                    ->label('Import CSV')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->form([
                        Forms\Components\FileUpload::make('csv_file')
                            ->label('Upload Result File')
                            ->acceptedFileTypes(['text/csv', 'application/vnd.ms-excel'])
                            ->required(),
                    ])
                    ->action(function (array $data, \App\Filament\Resources\RaceResource\RelationManagers\ResultsRelationManager $livewire) {
                        // Obtener ID de la carrera actual
                        $raceId = $livewire->getOwnerRecord()->id;
                        
                        // Ejecutar importación
                        $file = storage_path('app/public/' . $data['csv_file']);
                        \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\RaceResultsImport($raceId), $file);
                        
                        // Notificación de éxito
                        \Filament\Notifications\Notification::make()
                            ->title('Results Imported Successfully')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\CreateAction::make()
                    ->label('Add Result')
                    ->modalHeading('Register Race Result')
                    ->createAnother(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}