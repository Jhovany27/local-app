<?php

namespace App\Filament\Resources\DetallePedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DetallePedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('det_cantidad')
                    ->numeric(),
                TextEntry::make('det_precio_unitario')
                    ->numeric(),
                TextEntry::make('det_subtotal')
                    ->numeric(),
                TextEntry::make('det_fk_producto')
                    ->numeric(),
                TextEntry::make('det_fk_pedido')
                    ->numeric(),
            ]);
    }
}
