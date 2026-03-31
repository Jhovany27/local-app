<?php

namespace App\Filament\Resources\CategoriaProductos;

use App\Filament\Resources\CategoriaProductos\Pages\CreateCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Pages\EditCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Pages\ListCategoriaProductos;
use App\Filament\Resources\CategoriaProductos\Pages\ViewCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Schemas\CategoriaProductoForm;
use App\Filament\Resources\CategoriaProductos\Schemas\CategoriaProductoInfolist;
use App\Filament\Resources\CategoriaProductos\Tables\CategoriaProductosTable;
use App\Models\CategoriaProducto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CategoriaProductoResource extends Resource
{
    protected static ?string $model = CategoriaProducto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return CategoriaProductoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return CategoriaProductoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriaProductosTable::configure($table);
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
            'index' => ListCategoriaProductos::route('/'),
            'create' => CreateCategoriaProducto::route('/create'),
            'view' => ViewCategoriaProducto::route('/{record}'),
            'edit' => EditCategoriaProducto::route('/{record}/edit'),
        ];
    }
}
