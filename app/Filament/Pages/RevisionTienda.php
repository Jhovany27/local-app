<?php

namespace App\Filament\Pages;

use App\Models\Tienda;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class RevisionTienda extends Page
{
    protected string $view = 'filament.pages.revision-tienda';
    protected static bool $shouldRegisterNavigation = false;

    public Tienda $tienda;

    public function mount(): void
    {
        /** @var string $id */
        $id = (int) request()->query('id', 0);
        abort_unless($id, 404);

        $this->tienda = Tienda::with([
            'user.persona',
            'fachada',
            'documentos.tipo_documento',
        ])->findOrFail($id);

        abort_unless(
            $this->tienda->tie_estado === Tienda::ESTADO_PENDIENTE,
            403,
            'Esta tienda ya fue procesada.'
        );
    }

    public function aprobar(): void
    {
        DB::transaction(function () {
            $this->tienda->tie_estado         = Tienda::ESTADO_APROBADA;
            $this->tienda->tie_motivo_rechazo = null;
            $this->tienda->save();

            $user = $this->tienda->user;
            if ($user && !$user->hasRol('tienda')) {
                $user->roles()->attach(4);
            }
        });

        Notification::make()
            ->title('Tienda aprobada')
            ->body("Se aprobó {$this->tienda->tie_nombre} y se asignó el rol de tienda al usuario.")
            ->success()
            ->send();

        $this->redirect(route('filament.admin.pages.dashboard'));
    }

    // Acción con formulario para pedir motivo
    public function rechazar(array $data): void
    {
        $motivo = $data['motivo'] ?? null;

        $this->tienda->tie_estado         = Tienda::ESTADO_RECHAZADA;
        $this->tienda->tie_motivo_rechazo = $motivo;
        $this->tienda->save();

        Notification::make()
            ->title('Tienda rechazada')
            ->body("Se rechazó la solicitud de {$this->tienda->tie_nombre}.")
            ->danger()
            ->send();

        $this->redirect(route('filament.admin.pages.dashboard'));
    }

    protected function getForms(): array
    {
        return [
            'rechazarForm' => $this->makeForm()
                ->schema([
                    Textarea::make('motivo')
                        ->label('Motivo del rechazo')
                        ->placeholder('Explica al usuario por qué se rechazó su solicitud...')
                        ->required()
                        ->rows(4),
                ]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('aprobar')
                ->label('Aprobar tienda')
                ->color('success') // ← ya es verde, está bien
                ->icon('heroicon-o-check-circle')
                ->requiresConfirmation()
                ->modalHeading('¿Aprobar esta tienda?')
                ->modalDescription('Se asignará el rol de tienda al propietario.')
                ->action(fn() => $this->aprobar()),

            Action::make('rechazar')
                ->label('Rechazar')
                ->color('danger') // ← rojo, está bien
                ->icon('heroicon-o-x-circle')
                ->form([
                    Textarea::make('motivo')
                        ->label('Motivo del rechazo')
                        ->placeholder('Explica al usuario por qué se rechazó su solicitud...')
                        ->required()
                        ->rows(4),
                ])
                ->modalHeading('Rechazar tienda')
                ->modalDescription('El usuario verá este mensaje al iniciar sesión.')
                ->modalSubmitActionLabel('Confirmar rechazo')
                ->action(function (array $data): void {
                    $this->rechazar($data);
                }),
        ];
    }
}
