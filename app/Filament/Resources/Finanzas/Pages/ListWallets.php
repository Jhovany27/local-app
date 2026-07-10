<?php

namespace App\Filament\Resources\Finanzas\Pages;

use App\Filament\Resources\Finanzas\WalletResource;
use Filament\Resources\Pages\ListRecords;

class ListWallets extends ListRecords
{
    protected static string $resource = WalletResource::class;

    protected function getHeaderActions(): array { return []; }
}
