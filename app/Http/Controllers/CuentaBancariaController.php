<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CuentaBancariaController extends Controller
{
    // ── TIENDA ────────────────────────────────────────────
    public function updateTienda(Request $request)
    {
        $request->validate([
            'tie_numero_cuenta' => ['required', 'digits:18'],
        ], [
            'tie_numero_cuenta.required' => 'El número de cuenta es obligatorio.',
            'tie_numero_cuenta.digits'   => 'La CLABE debe tener exactamente 18 dígitos.',
        ]);

        /** @var \App\Models\User $user */
        $user     = Auth::user();
        $tiendaId = (int) session('store_tienda_id');

        $tienda = $user->tiendas()->where('tie_id', $tiendaId)->firstOrFail();
        $tienda->update(['tie_numero_cuenta' => $request->tie_numero_cuenta]);

        return back()->with('cuenta_ok', 'Número de cuenta actualizado correctamente.');
    }

    // ── REPARTIDOR ────────────────────────────────────────
    public function showRepartidor()
    {
        /** @var \App\Models\User $user */
        $user       = Auth::user();
        $repartidor = $user->repartidors()->firstOrFail();

        return view('repartidor.cuenta', compact('repartidor'));
    }

    public function updateRepartidor(Request $request)
    {
        $request->validate([
            'rep_numero_cuenta' => ['required', 'digits:18'],
        ], [
            'rep_numero_cuenta.required' => 'El número de cuenta es obligatorio.',
            'rep_numero_cuenta.digits'   => 'La CLABE debe tener exactamente 18 dígitos.',
        ]);

        /** @var \App\Models\User $user */
        $user       = Auth::user();
        $repartidor = $user->repartidors()->firstOrFail();
        $repartidor->update(['rep_numero_cuenta' => $request->rep_numero_cuenta]);

        return redirect()->route('repartidor.perfil')
            ->with('cuenta_ok', 'Número de cuenta actualizado correctamente.');
    }
}
