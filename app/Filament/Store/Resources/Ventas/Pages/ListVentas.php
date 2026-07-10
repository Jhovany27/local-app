<?php

namespace App\Filament\Store\Resources\Ventas\Pages;

use App\Filament\Store\Resources\Ventas\VentaResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListVentas extends ListRecords
{
    protected static string $resource = VentaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('corte_caja')
                ->label('Corte de caja')
                ->icon('heroicon-o-calculator')
                ->color('success')
                ->url('/store/corte-caja'),
        ];
    }
}