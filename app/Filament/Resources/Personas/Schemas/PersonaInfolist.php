<?php

namespace App\Filament\Resources\Personas\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PersonaInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('per_nombre'),
                TextEntry::make('per_paterno'),
                TextEntry::make('per_materno'),
                TextEntry::make('per_telefono')
                    ->numeric(),
                TextEntry::make('per_fecha_registro')
                    ->dateTime(),
                TextEntry::make('per_estado')
                    ->badge(),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
