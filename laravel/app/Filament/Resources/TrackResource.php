<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackResource\Pages;
use App\Models\Track;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrackResource extends Resource
{
    protected static ?string $model = Track::class;

    protected static ?string $navigationIcon = 'heroicon-o-map'; // Icono de mapa
    protected static ?string $navigationLabel = 'Tracks';
    protected static ?string $modelLabel = 'Track';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Track Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ex: Silverstone GP'),

                Forms\Components\TextInput::make('country_code')
                    ->label('Country Code (ISO)')
                    ->maxLength(2)
                    ->required()
                    ->placeholder('Ex: GB'),

                Forms\Components\TextInput::make('length_km')
                    ->label('Length (km)')
                    ->numeric()
                    ->step(0.001) // Permite decimales
                    ->suffix('km'),

                // --- AQUÍ ESTÁ LA MAGIA DE LA IMAGEN ---
                Forms\Components\FileUpload::make('layout_image_url')
                    ->label('Track Layout')
                    ->image() // Solo permite imágenes
                    ->directory('tracks') // Se guardan en storage/app/public/tracks
                    ->visibility('public'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Mostramos la imagen en pequeñito en la tabla
                Tables\Columns\ImageColumn::make('layout_image_url')
                    ->label('Layout')
                    ->circular(), // Queda muy elegante redondo

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('country_code')
                    ->label('Country')
                    ->badge(), // Queda como una etiqueta

                Tables\Columns\TextColumn::make('length_km')
                    ->label('Length')
                    ->suffix(' km'),
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
            'index' => Pages\ListTracks::route('/'),
            'create' => Pages\CreateTrack::route('/create'),
            'edit' => Pages\EditTrack::route('/{record}/edit'),
        ];
    }
}