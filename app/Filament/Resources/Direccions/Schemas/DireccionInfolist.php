<?php

namespace App\Filament\Resources\Direccions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DireccionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('drc_calle'),
                TextEntry::make('drc_numero'),
                TextEntry::make('drc_colonia'),
                TextEntry::make('drc_ciudad'),
                TextEntry::make('drc_estado'),
                TextEntry::make('drc_codigo_postal')
                    ->numeric(),
                TextEntry::make('drc_referencias')
                    ->columnSpanFull(),
                TextEntry::make('drc_latitud')
                    ->numeric(),
                TextEntry::make('drc_longitud')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
