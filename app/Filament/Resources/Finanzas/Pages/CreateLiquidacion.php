<?php

namespace App\Filament\Resources\Finanzas\Pages;

use App\Filament\Resources\Finanzas\LiquidacionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLiquidacion extends CreateRecord
{
    protected static string $resource = LiquidacionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['liq_estado']         = 'pendiente';
        $data['liq_fecha_creacion'] = now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
