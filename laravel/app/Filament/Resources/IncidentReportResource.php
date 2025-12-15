<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IncidentReportResource\Pages;
use App\Filament\Resources\IncidentReportResource\RelationManagers;
use App\Models\IncidentReport;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IncidentReportResource extends Resource
{
    protected static ?string $model = IncidentReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Incident Details')
                    ->schema([
                        Forms\Components\Select::make('race_id')
                            ->relationship('race', 'title') // Ojo: title puede estar vacío, mejor concatenar con circuito
                            ->disabled(), // El admin no cambia la carrera, solo la ve
                        
                        Forms\Components\Group::make([
                            Forms\Components\Select::make('reporter_id')
                                ->relationship('reporter', 'name')
                                ->label('Reporter')
                                ->disabled(),
                            
                            Forms\Components\Select::make('reported_id')
                                ->relationship('reported', 'name')
                                ->label('Accused Driver')
                                ->disabled(),
                        ])->columns(2),

                        Forms\Components\TextInput::make('lap_corner')
                            ->label('Lap / Turn')
                            ->disabled(),

                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->disabled(),

                        Forms\Components\TextInput::make('video_url')
                            ->label('Video Proof')
                            ->suffixIcon('heroicon-m-video-camera')
                            ->url() // Valida que sea URL
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Stewards Decision')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending Review',
                                'investigating' => 'Under Investigation',
                                'resolved' => 'Resolved (Penalty Applied)',
                                'dismissed' => 'Dismissed (No Action)',
                            ])
                            ->default('pending')
                            ->required(),

                        Forms\Components\TextInput::make('penalty_applied')
                            ->label('Penalty (e.g. +5s)')
                            ->placeholder('No Action'),

                        Forms\Components\Textarea::make('steward_notes')
                            ->label('Explanation')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y')->label('Date'),
                Tables\Columns\TextColumn::make('race.track.name')->label('Race'),
                Tables\Columns\TextColumn::make('reporter.name')->label('Reporter'),
                Tables\Columns\TextColumn::make('reported.name')->label('Accused')->color('danger'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'investigating' => 'warning',
                        'resolved' => 'success',
                        'dismissed' => 'danger',
                    }),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListIncidentReports::route('/'),
            'create' => Pages\CreateIncidentReport::route('/create'),
            'edit' => Pages\EditIncidentReport::route('/{record}/edit'),
        ];
    }
}
