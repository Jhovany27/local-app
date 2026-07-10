<?php

namespace App\Services;

use App\Models\Tienda;
use Filament\Notifications\Notification;

class TiendaNotificacion
{
    public static function enviar(
        int $tiendaId,
        string $titulo,
        string $cuerpo,
        string $color = 'info',
        string $icono = 'heroicon-o-bell'
    ): void {
        $user = Tienda::with('user')->find($tiendaId)?->user;
        if (! $user) return;

        $n = Notification::make()
            ->title($titulo)
            ->body($cuerpo)
            ->icon($icono);

        match ($color) {
            'success' => $n->success(),
            'warning' => $n->warning(),
            'danger'  => $n->danger(),
            default   => $n->info(),
        };

        $n->sendToDatabase($user);
    }
}
