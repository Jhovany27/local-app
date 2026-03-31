<?php

namespace App\Filament\Resources\Fachadas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FachadaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('fac_ruta'),
                TextEntry::make('fac_fk_tienda')
                    ->numeric(),
            ]);
    }
}
