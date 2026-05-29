<?php

namespace App\Http\Controllers;


use App\Models\DetallePedido;
use App\Models\EstadoPedido;
use App\Models\Pago;
use App\Models\Pedido;
use App\Models\Producto;
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
        $pedido->update(['ped_total' => $pedido->detalles()->sum('det_subtotal')]);
    }

    private function obtenerCarritoBD(int $clienteId, int $tiendaId): Pedido
    {
        return Pedido::firstOrCreate(
            [
                'ped_fk_cliente' => $clienteId,
                'ped_fk_tienda'  => $tiendaId,
                'ped_estado'     => 'carrito',
            ],
            [
                'ped_codigo'       => 'CAR-' . strtoupper(Str::random(8)),
                'ped_fecha_pedido' => now(),
                'ped_total'        => 0,
                'ped_tipo_entrega' => 'domicilio',
            ]
        );
    }

    // ── VER CARRITO ───────────────────────────────────────
    public function index()
    {
        // Si está logueado → carrito desde BD
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

        // Sin login → carrito desde sesión
        $carrito  = session()->get('carrito', []);
        $pedidos  = collect(); // vacío, la vista usa $carrito cuando no hay sesión
        return view('cliente.carrito', compact('pedidos', 'carrito'));
    }

    // ── AGREGAR ───────────────────────────────────────────
    public function agregar(Producto $producto)
    {
        // Sin login → guardar en sesión
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

        // Con login → guardar en BD
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
        // Sin login → sesión
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

        $detalle = DetallePedido::findOrFail($request->detalle_id);
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
        // Sin login → sesión
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

        $detalle = DetallePedido::findOrFail($request->detalle_id);
        if ($detalle->det_cantidad <= 1) {
            $pedido = $detalle->pedido;
            $detalle->delete();
            $this->recalcularTotal($pedido);
        } else {
            $nueva = $detalle->det_cantidad - 1;
            $detalle->update([
                'det_cantidad' => $nueva,
                'det_subtotal' => $nueva * $detalle->det_precio_unitario,
            ]);
            $this->recalcularTotal($detalle->pedido);
        }
        return back();
    }

    // ── ELIMINAR ──────────────────────────────────────────
    public function eliminar(Request $request)
    {
        // Sin login → sesión
        if (!Auth::check()) {
            $carrito = session()->get('carrito', []);
            unset($carrito[$request->producto_id]);
            session()->put('carrito', $carrito);
            return back();
        }

        $detalle = DetallePedido::findOrFail($request->detalle_id);
        $pedido  = $detalle->pedido;
        $detalle->delete();
        $this->recalcularTotal($pedido);
        return back();
    }

    // ── CHECKOUT (requiere login) ─────────────────────────
    public function checkout(int $pedidoId)
    {
        // Sin login → redirigir a login con intención de volver
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

        return view('cliente.checkout', compact('pedido', 'direccion'));
    }

    // ── CONFIRMAR (requiere login) ────────────────────────
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

        $pedido = Pedido::where('ped_id', $pedidoId)
            ->where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', 'carrito')
            ->firstOrFail();

        DB::transaction(function () use ($pedido, $request) {
            $pedido->update([
                'ped_estado'       => 'pendiente',
                'ped_tipo_entrega' => $request->tipo_entrega,
                'ped_codigo'       => 'PED-' . strtoupper(Str::random(8)),
                'ped_fecha_pedido' => now(),
            ]);

            EstadoPedido::create([
                'esp_nombre'       => 'Pendiente',
                'esp_fecha_cambio' => now(),
                'esp_fk_pedido'    => $pedido->ped_id,
            ]);

            Pago::create([
                'pag_monto'       => $pedido->ped_total,
                'pag_estado'      => 'Pendiente',
                'pag_metodo_pago' => 'efectivo',
                'pag_fecha'       => now(),
                'pag_fk_pedido'   => $pedido->ped_id,
            ]);
        });

        return redirect()->route('cliente.pedidos')
            ->with('success', '¡Pedido realizado! La tienda lo está preparando.');
    }

    // ── MIS PEDIDOS (requiere login) ──────────────────────
    public function misPedidos()
    {
        if (!Auth::check()) {
            return redirect()->route('cliente.login');
        }

        $clienteId = $this->getClienteId();
        abort_unless($clienteId, 403);

        $pedidos = Pedido::with(['tienda.fachada', 'detalles.producto'])
            ->where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', '!=', 'carrito')
            ->latest('ped_fecha_pedido')
            ->get();

        return view('cliente.mis-pedidos', compact('pedidos'));
    }

    // ── MIGRAR SESIÓN A BD (al hacer login) ───────────────
    public static function migrarSesionABD(): void
    {
        $carrito = session()->get('carrito', []);
        if (empty($carrito) || !Auth::check()) return;

        $clienteId = Auth::user()->cliente?->cli_id;
        if (!$clienteId) return;

        foreach (collect($carrito)->groupBy('tienda_id') as $tiendaId => $items) {
            $pedido = Pedido::firstOrCreate(
                [
                    'ped_fk_cliente' => $clienteId,
                    'ped_fk_tienda'  => $tiendaId,
                    'ped_estado'     => 'carrito',
                ],
                [
                    'ped_codigo'       => 'CAR-' . strtoupper(Str::random(8)),
                    'ped_fecha_pedido' => now(),
                    'ped_total'        => 0,
                    'ped_tipo_entrega' => 'domicilio',
                ]
            );

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