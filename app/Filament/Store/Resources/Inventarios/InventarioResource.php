<?php

namespace App\Filament\Store\Resources\Inventarios;


use App\Filament\Store\Resources\Inventarios\Schemas\InventarioForm;
use App\Filament\Store\Resources\Inventarios\Tables\InventariosTable;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class InventarioResource extends Resource
{
    protected static ?string $model = \App\Models\Producto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;


    protected static ?string $navigationLabel = 'Inventario';

    public static function canCreate(): bool
    {
        return false;
    }

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return InventarioForm::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $tiendaId = (int) session('store_tienda_id');

        $pertenece = $tiendaId && $user->tiendas()->where('tie_id', $tiendaId)->exists();

        if (! $pertenece) {
            return parent::getEloquentQuery()->whereRaw('1 = 0');
        }

        return parent::getEloquentQuery()
            ->with('inventario')
            ->where('pro_fk_tienda', $tiendaId);
    }

    public static function table(Table $table): Table
    {
        return InventariosTable::configure($table);
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
            'index' => Pages\ListInventarios::route('/'),
        ];
    }
}
