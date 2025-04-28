<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImageStyleResource\Pages;
use App\Filament\Resources\ImageStyleResource\RelationManagers;
use App\Models\ImageStyle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;

class ImageStyleResource extends Resource
{
    protected static ?string $model = ImageStyle::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                ->required()
                ->maxLength(255),

                TextInput::make('code')
                ->required()
                ->maxLength(255),
                
            Select::make('category_id')
                ->relationship('category', 'category')
                ->required(),
                
            Textarea::make('description')
                ->columnSpanFull(),
                
            Select::make('tags')
                ->relationship('tags', 'tag')
                ->multiple()
                ->preload(),
                
            FileUpload::make('image')
                ->directory('image-styles')
                ->required()
                ->image(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                ImageColumn::make('image')
                ->label('Image')
                ->disk('public') // sesuaikan dengan disk di config/filesystems.php
                ->height(100)
                ->width(100)
                ->toggleable(),
                
                TextColumn::make('title')
                ->searchable(),

                TextColumn::make('code')
                ->searchable(),
                
            TextColumn::make('category.category')
                ->sortable(),
                
            TextColumn::make('tags.tag')
                ->listWithLineBreaks(),
                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListImageStyles::route('/'),
            'create' => Pages\CreateImageStyle::route('/create'),
            'edit' => Pages\EditImageStyle::route('/{record}/edit'),
        ];
    }
}
