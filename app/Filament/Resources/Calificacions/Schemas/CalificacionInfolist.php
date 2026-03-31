<?php

namespace App\Filament\Resources\Calificacions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class CalificacionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('cal_puntuacion')
                    ->numeric(),
                TextEntry::make('cal_comentario')
                    ->columnSpanFull(),
                TextEntry::make('cal_fecha')
                    ->dateTime(),
                TextEntry::make('cal_fk_tienda')
                    ->numeric(),
                TextEntry::make('cal_fk_cliente')
                    ->numeric(),
            ]);
    }
}
