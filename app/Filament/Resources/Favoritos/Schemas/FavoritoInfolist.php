<?php

namespace App\Filament\Resources\Favoritos\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FavoritoInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('fav_fk_tienda')
                    ->numeric(),
                TextEntry::make('fav_fk_cliente')
                    ->numeric(),
            ]);
    }
}
