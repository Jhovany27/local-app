<?php

namespace App\Filament\Resources\Pedidos;

use App\Filament\Resources\Pedidos\Tables\PedidosTable;
use App\Models\Pedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class PedidoResource extends Resource
{
    protected static ?string $model = Pedido::class;
    protected static string|BackedEnum|null $navigationIcon = "heroicon-o-clipboard-document-list";
    protected static ?string $navigationLabel = "Pedidos";
    protected static string|\UnitEnum|null $navigationGroup = 'Gestión';
    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([]);
    }

    public static function table(Table $table): Table
    {
        return PedidosTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->with(["cliente.user.persona", "tienda", "pago", "asignacion.repartidor.user.persona"])
            ->whereNotIn("ped_estado", ["carrito"])
            ->latest("ped_fecha_pedido");
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            "index" => Pages\ListPedidos::route("/"),
            "view"  => Pages\ViewPedido::route("/{record}"),
        ];
    }
}