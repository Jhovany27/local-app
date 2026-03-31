<?php

namespace App\Filament\Resources\Favoritos\Pages;

use App\Filament\Resources\Favoritos\FavoritoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListFavoritos extends ListRecords
{
    protected static string $resource = FavoritoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
