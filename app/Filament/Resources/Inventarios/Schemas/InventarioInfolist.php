<?php

namespace App\Filament\Resources\Inventarios\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InventarioInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('inv_stock_actual')
                    ->numeric(),
                TextEntry::make('inv_stock_minimo')
                    ->numeric(),
                TextEntry::make('inv_actualizacion')
                    ->dateTime(),
                TextEntry::make('inv_fk_producto')
                    ->numeric(),
            ]);
    }
}
