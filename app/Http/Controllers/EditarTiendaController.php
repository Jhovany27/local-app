<?php

namespace App\Http\Controllers;

use App\Models\Fachada;
use App\Models\Tienda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DocumentoTienda;
use App\Models\TipoDocumentoTienda;

class EditarTiendaController extends Controller
{
    public function edit(Request $request)
    {
        $user = Auth::user();

        // ✅ Priorizar el ID del query param sobre la sesión
        $tiendaId = $request->query('id') ?? session('store_tienda_id');

        if (!$tiendaId) abort(403, 'No hay tienda seleccionada');

        $tienda = Tienda::with(['fachada', 'documentos'])
            ->where('tie_id', $tiendaId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // ✅ Guardar el ID correcto en sesión (el de la tienda que se edita)
        session(['store_tienda_id' => $tienda->tie_id]);

        return view('tienda.editar', compact('tienda'));
    }

    public function update(Request $request)
    {
        $user     = Auth::user();
        $tiendaId = session('store_tienda_id');

        if (!$tiendaId) abort(403, 'No hay tienda seleccionada');

        $tienda = Tienda::where('tie_id', $tiendaId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $estabaRechazada = $tienda->tie_estado == Tienda::ESTADO_RECHAZADA;

        $tienda->update([
            'tie_nombre'      => $request->tie_nombre,
            'tie_descripcion' => $request->tie_descripcion,
            'tie_telefono'    => $request->tie_telefono,
            'tie_direccion'   => $request->tie_direccion,
            'tie_latitud'     => $request->tie_latitud  ?? $tienda->tie_latitud,
            'tie_longitud'    => $request->tie_longitud ?? $tienda->tie_longitud,
            'tie_estado'      => $estabaRechazada
                ? Tienda::ESTADO_PENDIENTE
                : $tienda->tie_estado,
            'tie_motivo_rechazo' => null,
        ]);

        if ($request->hasFile('fachada')) {
            $ruta = $request->file('fachada')->store('fachadas', 'public');
            Fachada::updateOrCreate(
                ['fac_fk_tienda' => $tienda->tie_id],
                ['fac_ruta' => $ruta]
            );
        }

        if ($request->hasFile('ine')) {
            $tipoIne = TipoDocumentoTienda::find(1);
            $ruta    = $request->file('ine')->store('documentos_tienda', 'public');
            DocumentoTienda::updateOrCreate(
                ['dot_fk_tienda' => $tienda->tie_id, 'dot_fk_tipo_documento' => $tipoIne->tdt_id],
                ['dot_ruta' => $ruta, 'dot_fecha' => now()]
            );
        }

        if ($request->hasFile('comprobante')) {
            $tipoComp = TipoDocumentoTienda::find(2);
            $ruta     = $request->file('comprobante')->store('documentos_tienda', 'public');
            DocumentoTienda::updateOrCreate(
                ['dot_fk_tienda' => $tienda->tie_id, 'dot_fk_tipo_documento' => $tipoComp->tdt_id],
                ['dot_ruta' => $ruta, 'dot_fecha' => now()]
            );
        }

        //  Si estaba rechazada → regresar a pantalla de estado
        // Si venía del panel store → regresar a mi tienda
        if ($estabaRechazada) {
            return redirect()
                ->route('registro.tienda.pendiente')
                ->with('success', 'Información actualizada. Tu solicitud está en revisión nuevamente.');
        }

        return redirect()
            ->to(\App\Filament\Store\Pages\MiTienda::getUrl(panel: 'store'))
            ->with('success', 'Tienda actualizada correctamente.');
    }
}
