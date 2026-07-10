<?php

namespace App\Filament\Resources\Finanzas\Pages;

use App\Filament\Resources\Finanzas\LiquidacionResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;

class ListLiquidaciones extends ListRecords
{
    protected static string $resource = LiquidacionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generar')
                ->label('Generar corte periódico')
                ->icon('heroicon-o-bolt')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading('¿Generar liquidaciones ahora?')
                ->modalDescription('Se generarán liquidaciones pendientes para todas las tiendas y repartidores con saldo acumulado. Esta acción no se puede deshacer.')
                ->action(function () {
                    Artisan::call('liquidaciones:generar');
                    Notification::make()
                        ->title('Corte generado correctamente')
                        ->body('Las liquidaciones pendientes ya están disponibles.')
                        ->success()
                        ->send();
                }),

            CreateAction::make()->label('Nueva liquidación manual'),
        ];
    }
}
