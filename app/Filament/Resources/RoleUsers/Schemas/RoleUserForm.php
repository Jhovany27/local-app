<?php

namespace App\Filament\Resources\RoleUsers\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class RoleUserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('usr_fk_rol')
                    ->label('Rol')
                    ->relationship(
                        name: 'rol',
                        titleAttribute: 'rol_nombre',
                    )
                    ->searchable()
                    ->preload()
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
