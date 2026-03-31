<?php

namespace App\Filament\Resources\Favoritos;

use App\Filament\Resources\Favoritos\Pages\CreateFavorito;
use App\Filament\Resources\Favoritos\Pages\EditFavorito;
use App\Filament\Resources\Favoritos\Pages\ListFavoritos;
use App\Filament\Resources\Favoritos\Pages\ViewFavorito;
use App\Filament\Resources\Favoritos\Schemas\FavoritoForm;
use App\Filament\Resources\Favoritos\Schemas\FavoritoInfolist;
use App\Filament\Resources\Favoritos\Tables\FavoritosTable;
use App\Models\Favorito;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FavoritoResource extends Resource
{
    protected static ?string $model = Favorito::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return FavoritoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FavoritoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FavoritosTable::configure($table);
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
            'index' => ListFavoritos::route('/'),
            'create' => CreateFavorito::route('/create'),
            'view' => ViewFavorito::route('/{record}'),
            'edit' => EditFavorito::route('/{record}/edit'),
        ];
    }
}
