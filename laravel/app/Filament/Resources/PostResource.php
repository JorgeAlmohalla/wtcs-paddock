<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str; // Importante para el Slug

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper'; // Icono periódico
    protected static ?string $navigationLabel = 'News';
    protected static ?string $modelLabel = 'Article';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            // Magia: Al escribir, rellena el slug
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->disabled() // No dejar editarlo a mano para evitar errores
                            ->dehydrated(), // Pero sí enviarlo al guardar

                        Forms\Components\RichEditor::make('content')
                            ->label('Article Content')
                            ->required()
                            ->columnSpanFull(), // Ocupa todo el ancho
                    ]),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\FileUpload::make('image_url')
                            ->label('Cover Image')
                            ->image()
                            ->directory('news'),

                        Forms\Components\DatePicker::make('published_at')
                            ->label('Publish Date')
                            ->default(now())
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Cover'),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('published_at')
                    ->date()
                    ->sortable()
                    ->label('Date'),
            ])
            ->defaultSort('published_at', 'desc') // Las nuevas primero
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}