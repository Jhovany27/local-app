<?php

namespace App\Filament\Store\Pages;

use App\Models\Tienda;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class MiTienda extends Page
{
    protected string $view = 'filament.store.pages.mi-tienda';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $title = 'Mi Tienda';

    protected static ?string $navigationLabel = 'Mi tienda';

    public ?Tienda $tienda = null;

    public function mount(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user, 403);

        $tiendaId = session('store_tienda_id');

        abort_unless($tiendaId, 403, 'No hay tienda seleccionada.');

        $this->tienda = $user->tiendas()
            ->with([
                'fachada',
                'documentos.tipo_documento_tienda',
            ])
            ->where('tie_id', $tiendaId)
            ->first();

        abort_unless($this->tienda, 403, 'No tienes acceso a esta tienda.');
    }
}