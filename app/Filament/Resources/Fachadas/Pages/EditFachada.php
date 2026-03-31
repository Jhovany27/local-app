<?php

namespace App\Filament\Resources\Fachadas\Pages;

use App\Filament\Resources\Fachadas\FachadaResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFachada extends EditRecord
{
    protected static string $resource = FachadaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
