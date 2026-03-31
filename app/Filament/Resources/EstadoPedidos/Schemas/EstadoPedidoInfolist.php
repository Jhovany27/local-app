<?php

namespace App\Filament\Resources\EstadoPedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class EstadoPedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('esp_nombre'),
                TextEntry::make('esp_fecha_cambio')
                    ->dateTime(),
                TextEntry::make('esp_fk_pedido')
                    ->numeric(),
            ]);
    }
}
