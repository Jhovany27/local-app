<?php

namespace App\Filament\Resources\Repartidors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RepartidorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('rep_tipo_vehiculo')
                    ->badge(),
                TextEntry::make('rep_estado')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
