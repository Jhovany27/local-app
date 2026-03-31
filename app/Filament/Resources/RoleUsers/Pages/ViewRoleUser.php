<?php

namespace App\Filament\Resources\RoleUsers\Pages;

use App\Filament\Resources\RoleUsers\RoleUserResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRoleUser extends ViewRecord
{
    protected static string $resource = RoleUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
