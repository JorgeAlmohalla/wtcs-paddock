<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Drivers'; // Drivers en vez de Users
    protected static ?string $modelLabel = 'Driver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Driver Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create') // Solo obligatorio al crear
                    ->dehydrated(fn (?string $state): bool => filled($state)) // Solo se guarda si escribes algo
                    ->maxLength(255),
                
                // --- RELACIÓN CON EQUIPO ---
                Forms\Components\Select::make('team_id')
                    ->relationship('team', 'name') // Busca en la tabla 'teams', muestra el campo 'name'
                    ->label('Team')
                    ->searchable()
                    ->preload()
                    ->placeholder('Select a Team or leave empty (Free Agent)'),

                // --- DATOS DE SIMRACING ---
                Forms\Components\TextInput::make('steam_id')
                    ->label('Steam ID (64-bit)')
                    ->numeric()
                    ->maxLength(20),
                
                Forms\Components\TextInput::make('nationality')
                    ->label('Country Code')
                    ->default('ES')
                    ->maxLength(2),

                Forms\Components\CheckboxList::make('roles')
                    ->label('Roles')
                    ->options([
                        'admin' => 'Admin (Superuser)',
                        'steward' => 'Steward (Comisario)',
                        'team_principal' => 'Team Principal (Jefe Equipo)',
                        'driver' => 'Driver (Piloto)',
                    ])
                    ->columns(2)
                    ->required(),

                Forms\Components\Select::make('contract_type')
                    ->options([
                        'primary' => 'Primary Driver',
                        'reserve' => 'Reserve Driver',
                    ])
                    ->default('primary')
                    ->required(),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('team.name') // Magia: Muestra el nombre del equipo
                    ->label('Team')
                    ->placeholder('Free Agent') // Si es null pone esto
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nationality')
                    ->label('NAT')
                    ->badge(),
                
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'steward' => 'warning',
                        'driver' => 'success',
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}