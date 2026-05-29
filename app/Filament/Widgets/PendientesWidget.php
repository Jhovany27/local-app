<?php

namespace App\Filament\Widgets;

use App\Models\Repartidor;
use App\Models\Tienda;
use Filament\Widgets\Widget;

class PendientesWidget extends Widget
{
    protected string $view = 'filament.widgets.pendientes';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

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
        $rep->update(['rep_estado' => 1]);

        $user = $rep->user;
        if ($user && !$user->hasRol('repartidor')) {
            $user->roles()->attach(3);
        }

        \Filament\Notifications\Notification::make()
            ->title('Repartidor aprobado')
            ->success()->send();
    }

    public function rechazarRepartidor(int $id): void
    {
        Repartidor::findOrFail($id)->update(['rep_estado' => 2]);

        \Filament\Notifications\Notification::make()
            ->title('Repartidor rechazado')
            ->danger()->send();
    }
}