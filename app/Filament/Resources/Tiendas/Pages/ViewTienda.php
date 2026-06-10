<?php

namespace App\Filament\Resources\Tiendas\Pages;

use App\Filament\Resources\Tiendas\TiendaResource;
use App\Models\Tienda;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewTienda extends ViewRecord
{
    protected static string $resource = TiendaResource::class;

    protected string $view = 'filament.resources.tiendas.pages.view-tienda';

    protected function getHeaderActions(): array
    {
        return [
            // ── ACTIVAR ──────────────────────────────────────
            Action::make('activar')
                ->label('Activar tienda')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn() => (int)$this->record->tie_estado !== Tienda::ESTADO_APROBADA)
                ->requiresConfirmation()
                ->modalHeading('¿Activar esta tienda?')
                ->modalDescription('Se asignará el rol de tienda al propietario si no lo tiene.')
                ->action(function () {
                    DB::transaction(function () {
                        $this->record->update([
                            'tie_estado'         => Tienda::ESTADO_APROBADA,
                            'tie_motivo_rechazo' => null,
                        ]);

                        $user = $this->record->user;
                        if ($user && !$user->hasRol('tienda')) {
                            $user->roles()->attach(4);
                        }
                    });

                    Notification::make()
                        ->title('Tienda activada')
                        ->body("Se activó {$this->record->tie_nombre} y se asignó el rol de tienda.")
                        ->success()->send();

                    $this->refreshFormData([]);
                }),

            // ── PONER PENDIENTE ───────────────────────────────
            Action::make('pendiente')
                ->label('Marcar pendiente')
                ->color('warning')
                ->icon('heroicon-o-clock')
                ->visible(fn() => (int)$this->record->tie_estado !== Tienda::ESTADO_PENDIENTE)
                ->form([
                    Textarea::make('motivo')
                        ->label('Motivo (opcional)')
                        ->placeholder('¿Por qué se pone en revisión nuevamente?')
                        ->rows(3),
                ])
                ->modalHeading('¿Poner en revisión?')
                ->modalDescription('La tienda quedará pendiente de aprobación.')
                ->modalSubmitActionLabel('Confirmar')
                ->action(function (array $data) {
                    $this->record->update([
                        'tie_estado'         => Tienda::ESTADO_PENDIENTE,
                        'tie_motivo_rechazo' => $data['motivo'] ?? null,
                    ]);

                    // Quitar rol tienda si no tiene otras activas
                    $user = $this->record->user;
                    if ($user) {
                        $otrasActivas = $user->tiendas()
                            ->where('tie_id', '!=', $this->record->tie_id)
                            ->where('tie_estado', Tienda::ESTADO_APROBADA)
                            ->exists();

                        if (!$otrasActivas) {
                            $user->roles()->detach(4);
                        }
                    }

                    Notification::make()
                        ->title('Tienda en revisión')
                        ->warning()->send();

                    $this->refreshFormData([]);
                }),

            // ── RECHAZAR ─────────────────────────────────────
            Action::make('rechazar')
                ->label('Rechazar')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn() => (int)$this->record->tie_estado !== Tienda::ESTADO_RECHAZADA)
                ->form([
                    Textarea::make('motivo')
                        ->label('Motivo del rechazo')
                        ->placeholder('Explica al usuario por qué se rechazó su solicitud...')
                        ->required()
                        ->rows(4),
                ])
                ->modalHeading('Rechazar tienda')
                ->modalDescription('El usuario verá este motivo al iniciar sesión.')
                ->modalSubmitActionLabel('Confirmar rechazo')
                ->action(function (array $data) {
                    $this->record->update([
                        'tie_estado'         => Tienda::ESTADO_RECHAZADA,
                        'tie_motivo_rechazo' => $data['motivo'],
                    ]);

                    // Quitar rol tienda
                    $user = $this->record->user;
                    if ($user) {
                        $otrasActivas = $user->tiendas()
                            ->where('tie_id', '!=', $this->record->tie_id)
                            ->where('tie_estado', Tienda::ESTADO_APROBADA)
                            ->exists();

                        if (!$otrasActivas) {
                            $user->roles()->detach(4);
                        }
                    }

                    Notification::make()
                        ->title('Tienda rechazada')
                        ->body("Se rechazó la solicitud de {$this->record->tie_nombre}.")
                        ->danger()->send();

                    $this->refreshFormData([]);
                }),
        ];
    }
}