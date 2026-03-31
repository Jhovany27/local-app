<?php

namespace App\Filament\Resources\HistorialPedidos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class HistorialPedidoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('hip_fecha')
                    ->dateTime(),
                TextEntry::make('hip_fk_estado')
                    ->numeric(),
            ]);
    }
}
