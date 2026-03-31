<?php

namespace App\Filament\Resources\DisponibilidadRepars\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class DisponibilidadReparInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('dir_estado')
                    ->badge(),
                TextEntry::make('dir_actualizacion')
                    ->dateTime(),
                TextEntry::make('dir_fk_repartidor')
                    ->numeric(),
            ]);
    }
}
