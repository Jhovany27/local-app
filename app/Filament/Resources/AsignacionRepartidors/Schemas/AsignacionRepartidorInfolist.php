<?php

namespace App\Filament\Resources\AsignacionRepartidors\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AsignacionRepartidorInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('asr_fecha')
                    ->dateTime(),
                TextEntry::make('asr_estado')
                    ->numeric(),
                TextEntry::make('asr_fk_repartidor')
                    ->numeric(),
                TextEntry::make('asr_fk_pedido')
                    ->numeric(),
            ]);
    }
}
