<?php

namespace App\Filament\Resources\RoleUsers\Pages;

use App\Filament\Resources\RoleUsers\RoleUserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoleUsers extends ListRecords
{
    protected static string $resource = RoleUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
