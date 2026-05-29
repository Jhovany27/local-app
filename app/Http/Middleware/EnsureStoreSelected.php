<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class EnsureStoreSelected
{
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            return $next($request);
        }

        // Si no tiene rol tienda, Filament ya lo bloqueará con canAccessPanel
        // Aquí solo manejamos usuarios con rol tienda
        if (!$user->hasRol('tienda')) {
            return $next($request);
        }

        $currentRoute = $request->route()?->getName();

        $ignoreRoutes = [
            'filament.store.auth.login',
            'filament.store.auth.logout',
            'filament.store.pages.seleccionar-tienda',
        ];

        if (in_array($currentRoute, $ignoreRoutes)) {
            return $next($request);
        }

        $tiendas = $user->tiendas()
            ->where('tie_estado', \App\Models\Tienda::ESTADO_APROBADA)
            ->get();

        if ($tiendas->isEmpty()) {
            return redirect()->route('registro.tienda');
        }

        if ($tiendas->count() === 1) {
            session(['store_tienda_id' => $tiendas->first()->tie_id]);
            return $next($request);
        }

        if (!session()->has('store_tienda_id')) {
            return redirect()->route('filament.store.pages.seleccionar-tienda');
        }

        $tiendaId = session('store_tienda_id');
        $pertenece = $user->tiendas()->where('tie_id', $tiendaId)->exists();

        if (!$pertenece) {
            session()->forget('store_tienda_id');
            return redirect()->route('filament.store.pages.seleccionar-tienda');
        }

        return $next($request);
    }
}
