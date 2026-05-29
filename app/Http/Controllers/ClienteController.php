<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tienda;
use App\Models\Producto;
use App\Models\Pedido;
use App\Models\DetallePedido;

class ClienteController extends Controller
{
    public function index()
    {
        $tiendas = Tienda::where('tie_estado', 1)->with('fachada')->get();
        return view('cliente.index', compact('tiendas'));
    }

    public function show($id)
    {
        $tienda = Tienda::with('fachada')->findOrFail($id);

        $productos = Producto::where('pro_fk_tienda', $id)
            ->where('pro_estado', 1)
            ->get();

        return view('cliente.tienda', compact('tienda', 'productos'));
    }

    public function showProducto(int $id)
    {
        $producto = \App\Models\Producto::with([
            'fotos',
            'categoria_producto',
            'tienda',
            'inventario',
        ])->where('pro_estado', true)->findOrFail($id);


        $relacionados = \App\Models\Producto::with('fotos')
            ->where('pro_fk_tienda', $producto->pro_fk_tienda)
            ->where('pro_id', '!=', $producto->pro_id)
            ->where('pro_estado', true)
            ->limit(10)
            ->get();

        return view('cliente.producto', compact('producto', 'relacionados'));
    }

    function obtenerCarrito($clienteId, $tiendaId)
    {
        return Pedido::firstOrCreate(
            [
                'ped_fk_cliente' => $clienteId,
                'ped_fk_tienda' => $tiendaId,
                'ped_estado' => 'carrito',
            ],
            [
                'ped_codigo' => uniqid(),
                'ped_fecha_pedido' => now(),
                'ped_total' => 0,
                'ped_tipo_entrega' => 'domicilio',
            ]
        );
    }

    public function agregarAlCarrito(Request $request)
    {
        $producto = Producto::findOrFail($request->producto_id);

        $clienteId = 1; // temporal (luego lo haces dinámico)
        $tiendaId = $producto->pro_fk_tienda;

        $pedido = $this->obtenerCarrito($clienteId, $tiendaId);

        $detalle = DetallePedido::where([
            'det_fk_pedido' => $pedido->ped_id,
            'det_fk_producto' => $producto->pro_id
        ])->first();

        if ($detalle) {
            $detalle->increment('det_cantidad', 1);
            $detalle->update([
                'det_subtotal' => $detalle->det_cantidad * $detalle->det_precio_unitario
            ]);
        } else {
            DetallePedido::create([
                'det_fk_pedido' => $pedido->ped_id,
                'det_fk_producto' => $producto->pro_id,
                'det_cantidad' => 1,
                'det_precio_unitario' => $producto->pro_precio_venta,
                'det_subtotal' => $producto->pro_precio_venta,
            ]);
        }

        // actualizar total
        $pedido->update([
            'ped_total' => $pedido->detalles()->sum('det_subtotal')
        ]);

        return back()->with('success', 'Producto agregado al carrito');
    }

    public function carrito()
    {
        $clienteId = 1; // temporal

        $pedido = Pedido::where('ped_fk_cliente', $clienteId)
            ->where('ped_estado', 'carrito')
            ->with('detalles.producto')
            ->first();

        return view('cliente.carrito', compact('pedido'));
    }


    // SUMAR
    public function sumar(Request $request)
    {
        $detalle = DetallePedido::find($request->detalle_id);

        $detalle->increment('det_cantidad');

        $detalle->update([
            'det_subtotal' => $detalle->det_cantidad * $detalle->det_precio_unitario
        ]);

        $this->actualizarTotal($detalle->pedido);

        return back();
    }

    // RESTAR
    public function restar(Request $request)
    {
        $detalle = DetallePedido::find($request->detalle_id);

        if ($detalle->det_cantidad > 1) {
            $detalle->decrement('det_cantidad');

            $detalle->update([
                'det_subtotal' => $detalle->det_cantidad * $detalle->det_precio_unitario
            ]);
        }

        $this->actualizarTotal($detalle->pedido);

        return back();
    }

    // ELIMINAR
    public function eliminar(Request $request)
    {
        $detalle = DetallePedido::find($request->detalle_id);

        $pedido = $detalle->pedido;

        $detalle->delete();

        $this->actualizarTotal($pedido);

        return back();
    }

    // ACTUALIZAR TOTAL
    private function actualizarTotal($pedido)
    {
        $pedido->update([
            'ped_total' => $pedido->detalles()->sum('det_subtotal')
        ]);
    }
}
