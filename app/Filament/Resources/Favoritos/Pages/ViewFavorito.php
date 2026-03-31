<?php

namespace App\Filament\Resources\Favoritos\Pages;

use App\Filament\Resources\Favoritos\FavoritoResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewFavorito extends ViewRecord
{
    protected static string $resource = FavoritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
