<?php

namespace App\Filament\Resources\MovimientoInventarios\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class MovimientoInventarioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('mov_tipo')
                    ->badge(),
                TextEntry::make('mov_cantidad')
                    ->numeric(),
                TextEntry::make('mov_fecha')
                    ->dateTime(),
                TextEntry::make('mov_fk_producto')
                    ->numeric(),
            ]);
    }
}
