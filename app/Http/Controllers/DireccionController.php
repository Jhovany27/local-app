<?php

namespace App\Http\Controllers;

use App\Models\Direccion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DireccionController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $direcciones = $user->direccions()->get();
        return view('cliente.direcciones.index', compact('direcciones'));
    }

    public function create()
    {
        return view('cliente.direcciones.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'drc_codigo_postal' => ['required', 'digits:5'],
            'drc_estado'        => ['required', 'string', 'max:150'],
            'drc_calle'         => ['required', 'string', 'max:255'],
            'drc_numero'        => ['nullable', 'string', 'max:100'],
            'drc_colonia'       => ['nullable', 'string', 'max:150'],
            'drc_ciudad'        => ['nullable', 'string', 'max:150'],
            'drc_referencias'   => ['nullable', 'string', 'max:500'],
            'drc_latitud'       => ['nullable', 'numeric'],
            'drc_longitud'      => ['nullable', 'numeric'],
            'predeterminada'    => ['nullable'],
        ]);

        $direccion = Direccion::create([
            'drc_calle'         => $data['drc_calle'],
            'drc_numero'        => $data['drc_numero'] ?? '',
            'drc_colonia'       => $data['drc_colonia'] ?? '',
            'drc_ciudad'        => $data['drc_ciudad'] ?? '',
            'drc_estado'        => $data['drc_estado'],
            'drc_codigo_postal' => $data['drc_codigo_postal'],
            'drc_referencias'   => $data['drc_referencias'] ?? '',
            'drc_latitud'       => $data['drc_latitud'] ?? 0,
            'drc_longitud'      => $data['drc_longitud'] ?? 0,
            'user_id'           => Auth::id(),
        ]);

        if ($request->has('predeterminada')) {
            session(['direccion_id' => $direccion->drc_id]);
        }

        return redirect()->route('cliente.direcciones')
            ->with('success', 'Dirección guardada correctamente.');
    }

    public function show(int $id)
    {
        $direccion = Direccion::where('drc_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('cliente.direcciones.show', compact('direccion'));
    }

    public function edit(int $id)
    {
        $direccion = Direccion::where('drc_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('cliente.direcciones.edit', compact('direccion'));
    }

    public function update(Request $request, int $id)
    {
        $direccion = Direccion::where('drc_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $data = $request->validate([
            'drc_codigo_postal' => ['required', 'digits:5'],
            'drc_estado'        => ['required', 'string', 'max:150'],
            'drc_calle'         => ['required', 'string', 'max:255'],
            'drc_numero'        => ['nullable', 'string', 'max:100'],
            'drc_colonia'       => ['nullable', 'string', 'max:150'],
            'drc_ciudad'        => ['nullable', 'string', 'max:150'],
            'drc_referencias'   => ['nullable', 'string', 'max:500'],
            'drc_latitud'       => ['nullable', 'numeric'],
            'drc_longitud'      => ['nullable', 'numeric'],
            'predeterminada'    => ['nullable'],
        ]);

        $direccion->update([
            'drc_calle'         => $data['drc_calle'],
            'drc_numero'        => $data['drc_numero'] ?? '',
            'drc_colonia'       => $data['drc_colonia'] ?? '',
            'drc_ciudad'        => $data['drc_ciudad'] ?? '',
            'drc_estado'        => $data['drc_estado'],
            'drc_codigo_postal' => $data['drc_codigo_postal'],
            'drc_referencias'   => $data['drc_referencias'] ?? '',
            'drc_latitud'       => $data['drc_latitud'] ?? $direccion->drc_latitud,
            'drc_longitud'      => $data['drc_longitud'] ?? $direccion->drc_longitud,
        ]);

        if ($request->has('predeterminada')) {
            session(['direccion_id' => $direccion->drc_id]);
        }

        return redirect()->route('cliente.direcciones.show', $direccion->drc_id)
            ->with('success', 'Dirección actualizada correctamente.');
    }

    public function destroy(int $id)
    {
        $direccion = Direccion::where('drc_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Si era la activa, limpiar sesión
        if (session('direccion_id') == $direccion->drc_id) {
            session()->forget('direccion_id');
        }

        $direccion->delete();

        return redirect()->route('cliente.direcciones')
            ->with('success', 'Dirección eliminada.');
    }

    public function seleccionar(Request $request, int $id)
    {
        $direccion = Direccion::where('drc_id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        session(['direccion_id' => $direccion->drc_id]);

        $redirect = $request->input('redirect');
        return $redirect
            ? redirect($redirect)
            : redirect()->route('cliente.index');
    }
}
