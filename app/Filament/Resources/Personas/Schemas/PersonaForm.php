<?php

namespace App\Filament\Resources\Personas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersonaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('per_nombre')
                    ->required(),
                TextInput::make('per_paterno')
                    ->required(),
                TextInput::make('per_materno')
                    ->required(),
                TextInput::make('per_telefono')
                    ->tel()
                    ->required(),
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'email',
                        modifyQueryUsing: fn ($query) =>
                            $query->whereDoesntHave('persona')
                    )
                    ->searchable()
                    ->preload()
                    ->required(),
                            ]);
                    }
}
