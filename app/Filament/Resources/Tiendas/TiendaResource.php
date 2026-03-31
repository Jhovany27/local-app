<?php

namespace App\Filament\Resources\Tiendas;

use App\Filament\Resources\Tiendas\Pages\CreateTienda;
use App\Filament\Resources\Tiendas\Pages\EditTienda;
use App\Filament\Resources\Tiendas\Pages\ListTiendas;
use App\Filament\Resources\Tiendas\Pages\ViewTienda;
use App\Filament\Resources\Tiendas\Schemas\TiendaForm;
use App\Filament\Resources\Tiendas\Schemas\TiendaInfolist;
use App\Filament\Resources\Tiendas\Tables\TiendasTable;
use App\Models\Tienda;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TiendaResource extends Resource
{
    protected static ?string $model = Tienda::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TiendaForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TiendaInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TiendasTable::configure($table);
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
            'index' => ListTiendas::route('/'),
            'create' => CreateTienda::route('/create'),
            'view' => ViewTienda::route('/{record}'),
            'edit' => EditTienda::route('/{record}/edit'),
        ];
    }
}
