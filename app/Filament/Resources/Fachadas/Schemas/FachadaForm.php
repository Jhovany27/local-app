<?php

namespace App\Filament\Resources\Fachadas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FachadaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('fac_ruta')
                    ->required(),
                TextInput::make('fac_fk_tienda')
                    ->required()
                    ->numeric(),
            ]);
    }
}
