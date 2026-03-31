<?php

namespace App\Filament\Resources\Rols\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RolInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('rol_nombre'),
            ]);
    }
}
