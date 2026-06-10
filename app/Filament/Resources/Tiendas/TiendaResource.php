<?php

namespace App\Filament\Resources\Tiendas;

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

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $navigationLabel = 'Tiendas';

    protected static string|\UnitEnum|null $navigationGroup = 'Gestión';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return TiendasTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->with(['user.persona', 'fachada']);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTiendas::route('/'),
            'view'  => Pages\ViewTienda::route('/{record}'),  
        ];
    }
}