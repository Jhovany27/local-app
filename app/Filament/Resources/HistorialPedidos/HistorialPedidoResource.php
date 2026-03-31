<?php

namespace App\Filament\Resources\HistorialPedidos;

use App\Filament\Resources\HistorialPedidos\Pages\CreateHistorialPedido;
use App\Filament\Resources\HistorialPedidos\Pages\EditHistorialPedido;
use App\Filament\Resources\HistorialPedidos\Pages\ListHistorialPedidos;
use App\Filament\Resources\HistorialPedidos\Pages\ViewHistorialPedido;
use App\Filament\Resources\HistorialPedidos\Schemas\HistorialPedidoForm;
use App\Filament\Resources\HistorialPedidos\Schemas\HistorialPedidoInfolist;
use App\Filament\Resources\HistorialPedidos\Tables\HistorialPedidosTable;
use App\Models\HistorialPedido;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HistorialPedidoResource extends Resource
{
    protected static ?string $model = HistorialPedido::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HistorialPedidoForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return HistorialPedidoInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HistorialPedidosTable::configure($table);
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
            'index' => ListHistorialPedidos::route('/'),
            'create' => CreateHistorialPedido::route('/create'),
            'view' => ViewHistorialPedido::route('/{record}'),
            'edit' => EditHistorialPedido::route('/{record}/edit'),
        ];
    }
}
