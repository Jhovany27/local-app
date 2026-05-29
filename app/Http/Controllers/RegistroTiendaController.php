<?php

namespace App\Http\Controllers;

use App\Models\DocumentoTienda;
use App\Models\Fachada;
use App\Models\Tienda;
use App\Models\TipoDocumentoTienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RegistroTiendaController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user, 403);

        if ($user->hasRol('tienda')) {
            return redirect('/store');
        }

        $tiendaPendiente = $user->tiendas()
            ->where('tie_estado', 0)
            ->exists();

        if ($tiendaPendiente) {
            return redirect()->route('registro.tienda.pendiente');
        }

        $tiposDocumentos = TipoDocumentoTienda::all();

        return view('tienda.registro', compact('tiposDocumentos'));
    }

    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user, 403);

        if ($user->hasRol('tienda')) {
            return redirect('/store');
        }

        $request->validate([
            'tie_nombre' => 'required|string|max:255',
            'tie_descripcion' => 'required|string|max:500',
            'tie_telefono' => 'required|string|max:20',
            'tie_direccion' => 'required|string|max:255',
            'tie_latitud' => 'nullable|numeric',
            'tie_longitud' => 'nullable|numeric',

            'fachada' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',

            'ine' => 'required|file|mimes:pdf|max:4096',
            'comprobante' => 'required|file|mimes:pdf|max:4096',
        ]);

        DB::transaction(function () use ($request, $user) {

            // CREAR TIENDA
            $tienda = Tienda::create([
                'tie_nombre' => $request->tie_nombre,
                'tie_descripcion' => $request->tie_descripcion,
                'tie_telefono' => $request->tie_telefono,
                'tie_latitud' => $request->tie_latitud ?? 0,
                'tie_longitud' => $request->tie_longitud ?? 0,
                'tie_direccion' => $request->tie_direccion,
                'tie_estado' => 0,
                'tie_fecha_registro' => now(),
                'user_id' => $user->id,
            ]);

            // FACHADA
            if ($request->hasFile('fachada')) {
                $rutaFachada = $request->file('fachada')->store('fachadas', 'public');

                Fachada::create([
                    'fac_ruta' => $rutaFachada,
                    'fac_fk_tienda' => $tienda->tie_id,
                ]);
            }

            // TIPOS FIJOS (IMPORTANTE)
            $tipoINE = TipoDocumentoTienda::find(1);
            $tipoComprobante = TipoDocumentoTienda::find(2);
            // INE
            if ($request->hasFile('ine')) {
                $rutaINE = $request->file('ine')->store('documentos_tienda', 'public');

                DocumentoTienda::create([
                    'dot_ruta' => $rutaINE,
                    'dot_fecha' => now(),
                    'dot_fk_tienda' => $tienda->tie_id,
                    'dot_fk_tipo_documento' => $tipoINE->tdt_id,
                ]);
            }

            // COMPROBANTE
            if ($request->hasFile('comprobante')) {
                $rutaComp = $request->file('comprobante')->store('documentos_tienda', 'public');

                DocumentoTienda::create([
                    'dot_ruta' => $rutaComp,
                    'dot_fecha' => now(),
                    'dot_fk_tienda' => $tienda->tie_id,
                    'dot_fk_tipo_documento' => $tipoComprobante->tdt_id,
                ]);
            }
        });

        return redirect()->route('registro.tienda.pendiente');
    }

    public function pendiente()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        abort_unless($user, 403);

        if ($user->hasRol('tienda')) {
            return redirect('/store');
        }

        if (!$user->tiendas()->exists()) {
            return redirect()->route('registro.tienda');
        }

        // Obtener solo tiendas pendientes y rechazadas
        $tiendas = $user->tiendas()
            ->whereIn('tie_estado', [
                \App\Models\Tienda::ESTADO_PENDIENTE,
                \App\Models\Tienda::ESTADO_RECHAZADA,
            ])
            ->get();

        if ($tiendas->isEmpty()) {
            return redirect()->route('registro.tienda');
        }

        return view('tienda.estado', compact('tiendas'));
    }
}
