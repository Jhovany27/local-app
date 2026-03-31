<?php

namespace App\Filament\Resources\Notificacions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class NotificacionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('not_mensaje'),
                TextEntry::make('not_fecha'),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
