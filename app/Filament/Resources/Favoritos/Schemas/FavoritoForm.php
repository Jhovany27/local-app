<?php

namespace App\Filament\Resources\Favoritos\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FavoritoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('fav_fk_tienda')
                    ->required()
                    ->numeric(),
                TextInput::make('fav_fk_cliente')
                    ->required()
                    ->numeric(),
            ]);
    }
}
