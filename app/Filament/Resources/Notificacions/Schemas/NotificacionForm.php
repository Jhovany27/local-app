<?php

namespace App\Filament\Resources\Notificacions\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NotificacionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('not_mensaje')
                    ->required(),
                TextInput::make('not_fecha')
                    ->required(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
