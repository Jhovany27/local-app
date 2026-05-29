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
    public function edit()
    {
        $user     = Auth::user();
        $tiendaId = session('store_tienda_id');

        if (!$tiendaId) abort(403, 'No hay tienda seleccionada');

        $tienda = Tienda::with(['fachada', 'documentos'])
            ->where('tie_id', $tiendaId)
            ->where('user_id', $user->id)
            ->firstOrFail();

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

        $tienda->update([
            'tie_nombre'       => $request->tie_nombre,
            'tie_descripcion'  => $request->tie_descripcion,
            'tie_telefono'     => $request->tie_telefono,
            'tie_direccion'    => $request->tie_direccion,
            'tie_latitud'      => $request->tie_latitud  ?? $tienda->tie_latitud,
            'tie_longitud'     => $request->tie_longitud ?? $tienda->tie_longitud,
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

        return redirect()
            ->to(\App\Filament\Store\Pages\MiTienda::getUrl(panel: 'store'))
            ->with('success', 'Tienda actualizada correctamente.');
    }
}
