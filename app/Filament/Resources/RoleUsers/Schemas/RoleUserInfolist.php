<?php

namespace App\Filament\Resources\RoleUsers\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RoleUserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('usr_fk_rol')
                    ->numeric(),
                TextEntry::make('user_id')
                    ->numeric(),
            ]);
    }
}
