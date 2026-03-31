<?php

namespace App\Filament\Resources\Pedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('ped_codigo'),
                TextEntry::make('ped_fecha_pedido')
                    ->dateTime(),
                TextEntry::make('ped_total')
                    ->numeric(),
                TextEntry::make('ped_tipo_entrega')
                    ->badge(),
                TextEntry::make('ped_fk_cliente')
                    ->numeric(),
                TextEntry::make('ped_fk_tienda')
                    ->numeric(),
            ]);
    }
}
