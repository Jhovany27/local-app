<?php

namespace App\Filament\Resources\Favoritos\Pages;

use App\Filament\Resources\Favoritos\FavoritoResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditFavorito extends EditRecord
{
    protected static string $resource = FavoritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
