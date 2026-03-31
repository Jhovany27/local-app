<?php

namespace App\Filament\Resources\Tiendas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TiendaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tie_nombre')
                    ->required(),
                TextInput::make('tie_descripcion')
                    ->required(),
                TextInput::make('tie_telefono')
                    ->tel()
                    ->required(),
                TextInput::make('tie_latitud')
                    ->required()
                    ->numeric(),
                TextInput::make('tie_longitud')
                    ->required()
                    ->numeric(),
                TextInput::make('tie_direccion')
                    ->required(),
                Select::make('tie_estado')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ])
                    ->required(),
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'email',
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
