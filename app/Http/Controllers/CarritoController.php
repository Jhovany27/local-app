<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\EstadoPedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
use App\Services\EnvioCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CarritoController extends Controller
{
    // ── HELPERS ───────────────────────────────────────────

    private function getClienteId(): ?int
    {
        if (!Auth::check()) return null;
        return Auth::user()->cliente?->cli_id;
    }

    private function recalcularTotal(Pedido $pedido): void
    {
        $subtotal = $pedido->detalles()->sum('det_subtotal');
        $pedido->update(['ped_total' => $subtotal + $pedido->ped_costo_envio]);
    }

    private function getDetallePropio(int $detalleId): DetallePedido
    {
        $clienteId = $this->getClienteId();
        abort_unless($clienteId, 403);

        return DetallePedido::whereHas(
            'pedido',
            fn($q) => $q->where('ped_fk_cliente', $clienteId)->where('ped_estado', 'carrito')
        )->findOrFail($detalleId);
    }

    private function obtenerCarritoBD(int $clienteId, int $tiendaId): Pedido
    {
        return Pedido::where('ped_fk_cliente', $clienteId)
            ->where('ped_fk_tienda', $tiendaId)
            ->where('ped_estado', 'carrito')
            ->first()
            ?? Pedido::forceCreate([
                'ped_fk_cliente'   => $clienteId,
                'ped_fk_tienda'    => $tiendaId,
                'ped_estado'       => 'carrito',
                'ped_codigo'       => 'CAR-' . strtoupper(Str::random(8)),
                'ped_fecha_pedido' => now(),
                'ped_total'        => 0,
                'ped_costo_envio'  => 0,
                'ped_tipo_entrega' => 'domicilio',
            ]);
    }

    // ── VER CARRITO ───────────────────────────────────────
    public function index()
    {
        if (Auth::check()) {
            $clienteId = $this->getClienteId();
            $pedidos = $clienteId
                ? Pedido::with(['detalles.producto', 'tienda.fachada'])
                ->where('ped_fk_cliente', $clienteId)
                ->where('ped_estado', 'carrito')
                ->get()
                : collect();

            return view('cliente.carrito', compact('pedidos'));
        }

        $carrito = session()->get('carrito', []);
        $pedidos = collect();
        return view('cliente.carrito', compact('pedidos', 'carrito'));
    }

    // ── AGREGAR ───────────────────────────────────────────
    public function agregar(Producto $producto)
    {
        if (!Auth::check()) {
            $carrito = session()->get('carrito', []);
            $id = $producto->pro_id;

            if (isset($carrito[$id])) {
                $carrito[$id]['cantidad']++;
                $carrito[$id]['subtotal'] = $carrito[$id]['cantidad'] * $carrito[$id]['precio'];
            } else {
                $carrito[$id] = [
                    'nombre'    => $producto->pro_nombre,
                    'precio'    => $producto->pro_precio_venta,
                    'foto'      => $producto->foto_principal,
                    'cantidad'  => 1,
                    'subtotal'  => $producto->pro_precio_venta,
                    'tienda_id' => $producto->pro_fk_tienda,
                ];
            }
            session()->put('carrito', $carrito);
            return back()->with('success', 'Producto agregado');
        }

        $clienteId = $this->getClienteId();
        if (!$clienteId) return back();

        $pedido  = $this->obtenerCarritoBD($clienteId, $producto->pro_fk_tienda);
        $detalle = DetallePedido::where([
            'det_fk_pedido'   => $pedido->ped_id,
            'det_fk_producto' => $producto->pro_id,
        ])->first();

        if ($detalle) {
            $nueva = $detalle->det_cantidad + 1;
            $detalle->update([
                'det_cantidad' => $nueva,
                'det_subtotal' => $nueva * $detalle->det_precio_unitario,
            ]);
        } else {
            DetallePedido::create([
                'det_fk_pedido'       => $pedido->ped_id,
                'det_fk_producto'     => $producto->pro_id,
                'det_cantidad'        => 1,
                'det_precio_unitario' => $producto->pro_precio_venta,
                'det_subtotal'        => $producto->pro_precio_venta,
            ]);
        }

        $this->recalcularTotal($pedido);
        return back()->with('success', 'Producto agregado');
    }

    // ── SUMAR ─────────────────────────────────────────────
    public function sumar(Request $request)
    {
        if (!Auth::check()) {
            $carrito = session()->get('carrito', []);
            $id = $request->producto_id;
            if (isset($carrito[$id])) {
                $carrito[$id]['cantidad']++;
                $carrito[$id]['subtotal'] = $carrito[$id]['cantidad'] * $carrito[$id]['precio'];
            }
            session()->put('carrito', $carrito);
            return back();
        }

        $detalle = $this->getDetallePropio($request->detalle_id);
        $nueva   = $detalle->det_cantidad + 1;
        $detalle->update([
            'det_cantidad' => $nueva,
            'det_subtotal' => $nueva * $detalle->det_precio_unitario,
        ]);
        $this->recalcularTotal($detalle->pedido);
        return back();
    }

    // ── RESTAR ────────────────────────────────────────────
    public function restar(Request $request)
    {
        if (!Auth::check()) {
            $carrito = session()->get('carrito', []);
            $id = $request->producto_id;
            if (isset($carrito[$id])) {
                if ($carrito[$id]['cantidad'] > 1) {
                    $carrito[$id]['cantidad']--;
                    $carrito[$id]['subtotal'] = $carrito[$id]['cantidad'] * $carrito[$id]['precio'];
                } else {
                    unset($carrito[$id]);
                }
            }
            session()->put('carrito', $carrito);
            return back();
        }

        $detalle = $this->getDetallePropio($request->detalle_id);
        $pedido  = $detalle->pedido;

        if ($detalle->det_cantidad <= 1) {
            $detalle->delete();
        } else {
            $nueva = $detalle->det_cantidad - 1;
            $detalle->update([
                'det_cantidad' => $nueva,
                'det_subtotal' => $nueva * $detalle->det_precio_unitario,
            ]);
        }

        $this->recalcularTotal($pedido);

        if ($pedido->detalles()->count() === 0) {
            $pedido->delete();
        }

        return back();
    }

    // ── ELIMINAR ──────────────────────────────────────────
    public function eliminar(Request $request)
    {
        if (!Auth::check()) {
            $carrito = session()->get('carrito', []);
            unset($carrito[$request->producto_id]);
            session()->put('carrito', $carrito);
            return back();
        }

        $detalle = $this->getDetallePropio($request->detalle_id);
        $pedido  = $detalle->pedido;
        $detalle->delete();
        $this->recalcularTotal($pedido);

        if ($pedido->detalles()->count() === 0) {
            $pedido->delete();
        }

        return back();
    }

    // ── CHECKOUT ──────────────────────────────────────────
    public function checkout(int $pedidoId)
    {
        if (!Auth::check()) {
            return redirect()->route('cliente.login', ['redirect' => 'carrito']);
        }

        $clienteId = $this->getClienteId();
        abort_unless($clienteId, 403);

        $pedido = Pedido::with(['detalles.producto', 'tienda'])
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', 'carrito')
            ->firstOrFail();

        $direccion = session('direccion_id')
            ? \App\Models\Direccion::find(session('direccion_id'))
            : null;

        //  Calcular costo de envío si hay coordenadas
        $envio = null;
        if (
            $direccion?->drc_latitud && $direccion?->drc_longitud &&
            $pedido->tienda->tie_latitud && $pedido->tienda->tie_longitud
        ) {
            $envio = EnvioCalculator::calcular(
                (float) $pedido->tienda->tie_latitud,
                (float) $pedido->tienda->tie_longitud,
                (float) $direccion->drc_latitud,
                (float) $direccion->drc_longitud,
            );
        }

        return view('cliente.checkout', compact('pedido', 'direccion', 'envio'));
    }

    // ── CONFIRMAR ─────────────────────────────────────────
    public function confirmar(Request $request, int $pedidoId)
    {
        if (!Auth::check()) {
            return redirect()->route('cliente.login', ['redirect' => 'carrito']);
        }

        $clienteId = $this->getClienteId();
        abort_unless($clienteId, 403);

        $request->validate([
            'tipo_entrega' => ['required', 'in:domicilio,recoger'],
        ]);

        $pedido = Pedido::with('tienda')
            ->where('ped_id', $pedidoId)
            ->where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', 'carrito')
            ->firstOrFail();

        //  Calcular costo de envío al confirmar
        $costoEnvio   = 0;
        $subtotal     = $pedido->detalles()->sum('det_subtotal');

        if ($request->tipo_entrega === 'domicilio') {
            $direccion = session('direccion_id')
                ? \App\Models\Direccion::find(session('direccion_id'))
                : null;

            if (
                $direccion?->drc_latitud && $direccion?->drc_longitud &&
                $pedido->tienda->tie_latitud && $pedido->tienda->tie_longitud
            ) {
                $envio      = EnvioCalculator::calcular(
                    (float) $pedido->tienda->tie_latitud,
                    (float) $pedido->tienda->tie_longitud,
                    (float) $direccion->drc_latitud,
                    (float) $direccion->drc_longitud,
                );
                $costoEnvio = $envio['costo_envio'];
            }
        }

        DB::transaction(function () use ($pedido, $request, $subtotal, $costoEnvio) {
            $pedido->forceFill([
                'ped_estado'       => 'pendiente',
                'ped_tipo_entrega' => $request->tipo_entrega,
                'ped_codigo'       => 'PED-' . strtoupper(Str::random(8)),
                'ped_fecha_pedido' => now(),
                'ped_costo_envio'  => $costoEnvio,
                'ped_total'        => $subtotal + $costoEnvio,
                'ped_fk_direccion' => $request->tipo_entrega === 'domicilio'
                    ? session('direccion_id')
                    : null,
            ])->save();

            EstadoPedido::create([
                'esp_nombre'       => 'Pendiente',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);

            Pago::updateOrCreate(
                ['pag_fk_pedido' => $pedido->ped_id],
                [
                    'pag_monto'       => $pedido->ped_total,
                    'pag_estado'      => 'Pendiente',
                    'pag_metodo_pago' => 'Efectivo',
                    'pag_fecha'       => now(),
                ]
            );
        });

        return redirect()->route('cliente.pedidos')
            ->with('success', '¡Pedido realizado! La tienda lo está preparando.');
    }

    // ── MIS PEDIDOS ───────────────────────────────────────
    public function misPedidos()
    {
        /** @var \App\Models\User $user */
        $user    = Auth::user();
        $cliente = $user->cliente;

        abort_unless($cliente, 403);

        $pedidos = \App\Models\Pedido::with([
            'tienda',
            'detalles.producto',
            'asignacion',
        ])
            ->where('ped_fk_cliente', $cliente->cli_id)
            ->whereNotIn('ped_estado', ['carrito'])
            ->latest('ped_fecha_pedido')
            ->get();

        return view('cliente.mis-pedidos', compact('pedidos'));
    }

    // ── MIGRAR SESIÓN A BD ────────────────────────────────
    public static function migrarSesionABD(): void
    {
        $carrito = session()->get('carrito', []);
        if (empty($carrito) || !Auth::check()) return;

        $clienteId = Auth::user()->cliente?->cli_id;
        if (!$clienteId) return;

        foreach (collect($carrito)->groupBy('tienda_id') as $tiendaId => $items) {
            $pedido = Pedido::where('ped_fk_cliente', $clienteId)
                ->where('ped_fk_tienda', $tiendaId)
                ->where('ped_estado', 'carrito')
                ->first()
                ?? Pedido::forceCreate([
                    'ped_fk_cliente'   => $clienteId,
                    'ped_fk_tienda'    => $tiendaId,
                    'ped_estado'       => 'carrito',
                    'ped_codigo'       => 'CAR-' . strtoupper(Str::random(8)),
                    'ped_fecha_pedido' => now(),
                    'ped_total'        => 0,
                    'ped_costo_envio'  => 0,
                    'ped_tipo_entrega' => 'domicilio',
                ]);

            foreach ($items as $productoId => $item) {
                $producto = Producto::find($productoId);
                if (!$producto) continue;

                $detalle = DetallePedido::where([
                    'det_fk_pedido'   => $pedido->ped_id,
                    'det_fk_producto' => $productoId,
                ])->first();

                if ($detalle) {
                    $nueva = $detalle->det_cantidad + $item['cantidad'];
                    $detalle->update([
                        'det_cantidad' => $nueva,
                        'det_subtotal' => $nueva * $detalle->det_precio_unitario,
                    ]);
                } else {
                    DetallePedido::create([
                        'det_fk_pedido'       => $pedido->ped_id,
                        'det_fk_producto'     => $productoId,
                        'det_cantidad'        => $item['cantidad'],
                        'det_precio_unitario' => $item['precio'],
                        'det_subtotal'        => $item['subtotal'],
                    ]);
                }
            }

            $pedido->update(['ped_total' => $pedido->detalles()->sum('det_subtotal')]);
        }

        session()->forget('carrito');
    }
}
