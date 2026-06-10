<?php

namespace App\Filament\Store\Resources\Ventas;

use App\Filament\Store\Resources\Ventas\Tables\VentasTable;
use App\Models\Venta;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class VentaResource extends Resource
{
    protected static ?string $model = Venta::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingCart;

    protected static ?string $navigationLabel = 'Historial de ventas';

    protected static ?int $navigationSort = 6;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return VentasTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['detalles.producto'])
            ->where('ven_fk_tienda', session('store_tienda_id'))
            ->latest('ven_fecha');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVentas::route('/'),
            'view'  => Pages\ViewVenta::route('/{record}'),
        ];
    }
}
