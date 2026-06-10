<?php

namespace App\Filament\Widgets;

use App\Models\Repartidor;
use App\Models\Tienda;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;

class PendientesWidget extends Widget
{
    protected string $view = 'filament.widgets.pendientes';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    public ?int $rechazandoId = null;
    public string $motivoRechazoInput = '';

    public function getTiendasProperty()
    {
        return Tienda::with(['user.persona', 'fachada'])
            ->where('tie_estado', Tienda::ESTADO_PENDIENTE)
            ->latest('tie_fecha_registro')
            ->get();
    }

    public function getRepartidoresProperty()
    {
        return Repartidor::with(['user.persona', 'documentos.tipo_documento'])
            ->where('rep_estado', 0)
            ->latest('rep_id')
            ->get();
    }

    public function aprobarRepartidor(int $id): void
    {
        $rep  = Repartidor::findOrFail($id);
        $rep->rep_estado = 1;
        $rep->save();

        $user = $rep->user;
        if ($user && !$user->hasRol('repartidor')) {
            $user->roles()->attach(3);
        }

        Notification::make()->title('Repartidor aprobado')->success()->send();
    }

    public function abrirModalRechazo(int $id): void
    {
        $this->rechazandoId    = $id;
        $this->motivoRechazoInput = '';
    }

    public function cancelarRechazo(): void
    {
        $this->rechazandoId    = null;
        $this->motivoRechazoInput = '';
    }

    public function confirmarRechazo(): void
    {
        $this->validate([
            'motivoRechazoInput' => 'required|min:10',
        ], [
            'motivoRechazoInput.required' => 'Debes escribir un motivo.',
            'motivoRechazoInput.min'      => 'El motivo debe tener al menos 10 caracteres.',
        ]);

        $rep = Repartidor::findOrFail($this->rechazandoId);
        $rep->rep_estado           = 2;
        $rep->rep_motivo_rechazo   = $this->motivoRechazoInput;
        $rep->save();

        $this->rechazandoId    = null;
        $this->motivoRechazoInput = '';

        Notification::make()->title('Repartidor rechazado')->danger()->send();
    }
}