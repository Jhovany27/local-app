<?php

namespace App\Filament\Store\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class SeleccionarTienda extends Page
{
    protected string $view = 'filament.store.pages.seleccionar-tienda';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $title = '';

    public ?int $tienda_id = null;

    public function getTiendasProperty()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        return $user->tiendas()
            ->orderBy('tie_nombre')
            ->get();
    }

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user, 403);

        $tiendas = $user->tiendas()->get();

        abort_if($tiendas->isEmpty(), 403, 'No tienes tiendas asignadas.');

        if ($tiendas->count() === 1) {
            session(['store_tienda_id' => $tiendas->first()->tie_id]);

            // ✅ Así se redirige correctamente en Filament/Livewire
            $this->redirect(
                \App\Filament\Store\Resources\Productos\ProductoResource::getUrl()
            );

            return;
        }
    }

    public function seleccionar(int $tiendaId): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $pertenece = $user->tiendas()
            ->where('tie_id', $tiendaId)
            ->exists();

        abort_unless($pertenece, 403, 'Esa tienda no te pertenece.');

        session(['store_tienda_id' => $tiendaId]);

        // ✅ Igualmente aquí
        $this->redirect(
            \App\Filament\Store\Resources\Productos\ProductoResource::getUrl()
        );
    }
}