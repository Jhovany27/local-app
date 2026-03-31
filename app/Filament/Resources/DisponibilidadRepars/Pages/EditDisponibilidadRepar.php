<?php

namespace App\Filament\Resources\DisponibilidadRepars\Pages;

use App\Filament\Resources\DisponibilidadRepars\DisponibilidadReparResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditDisponibilidadRepar extends EditRecord
{
    protected static string $resource = DisponibilidadReparResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
