<?php

namespace App\Filament\Resources\Repartidors\Pages;

use App\Filament\Resources\Repartidors\RepartidorResource;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewRepartidor extends ViewRecord
{
    protected static string $resource = RepartidorResource::class;

    protected string $view = 'filament.resources.repartidors.pages.view-repartidor';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('aprobar')
                ->label('Aprobar repartidor')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => (int) $this->record->rep_estado !== 1)
                ->requiresConfirmation()
                ->modalHeading('¿Aprobar este repartidor?')
                ->modalDescription('Se asignará el rol de repartidor al usuario si no lo tiene.')
                ->action(function () {
                    $this->record->rep_estado         = 1;
                    $this->record->rep_motivo_rechazo = null;
                    $this->record->save();

                    $user = $this->record->user;
                    if ($user && !$user->hasRol('repartidor')) {
                        $user->roles()->attach(3);
                    }

                    Notification::make()
                        ->title('Repartidor aprobado')
                        ->body('El repartidor ya puede recibir pedidos.')
                        ->success()->send();

                    $this->refreshFormData([]);
                }),

            Action::make('pendiente')
                ->label('Marcar pendiente')
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->visible(fn () => (int) $this->record->rep_estado !== 0)
                ->requiresConfirmation()
                ->modalHeading('¿Poner en revisión?')
                ->modalDescription('El repartidor quedará pendiente de aprobación nuevamente.')
                ->action(function () {
                    $this->record->rep_estado = 0;
                    $this->record->save();

                    $user = $this->record->user;
                    if ($user) {
                        $user->roles()->detach(3);
                    }

                    Notification::make()->title('Repartidor en revisión')->warning()->send();

                    $this->refreshFormData([]);
                }),

            Action::make('rechazar')
                ->label('Rechazar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn () => (int) $this->record->rep_estado !== 2)
                ->form([
                    Textarea::make('motivo')
                        ->label('Motivo del rechazo')
                        ->placeholder('Explica al repartidor por qué se rechazó su solicitud...')
                        ->required()
                        ->rows(4),
                ])
                ->modalHeading('Rechazar repartidor')
                ->modalDescription('El repartidor verá este motivo al iniciar sesión.')
                ->modalSubmitActionLabel('Confirmar rechazo')
                ->action(function (array $data) {
                    $this->record->rep_estado         = 2;
                    $this->record->rep_motivo_rechazo = $data['motivo'];
                    $this->record->save();

                    $user = $this->record->user;
                    if ($user) {
                        $user->roles()->detach(3);
                    }

                    Notification::make()
                        ->title('Repartidor rechazado')
                        ->danger()->send();

                    $this->refreshFormData([]);
                }),
        ];
    }
}
