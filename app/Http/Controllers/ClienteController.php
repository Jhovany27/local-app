<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tienda;
use App\Models\Producto;
use App\Models\CategoriaProducto;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Favorito;
use App\Models\Direccion;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $user         = auth()->user();
        $sinDireccion = false;
        $direcciones  = collect();

        if ($user && $user->hasRol('cliente')) {
            $dirActiva = session('direccion_id')
                ? Direccion::find(session('direccion_id'))
                : null;

            if (!$dirActiva && !$request->boolean('sin_filtro')) {
                $sinDireccion = true;
                $direcciones  = $user->direccions()->get();
                $tiendas      = collect();
            } elseif ($dirActiva) {
                $tiendas = $this->filtrarTiendasPorDireccion($dirActiva);
            } else {
                $tiendas = Tienda::where('tie_estado', 1)->with('fachada')->get();
            }
        } else {
            $tiendas = Tienda::where('tie_estado', 1)->with('fachada')->get();
        }

        return view('cliente.index', compact('tiendas', 'sinDireccion', 'direcciones'));
    }

    private function filtrarTiendasPorDireccion(Direccion $dir)
    {
        $query = Tienda::where('tie_estado', 1)->with('fachada');

        if ($dir->drc_latitud && $dir->drc_longitud) {
            $lat = (float) $dir->drc_latitud;
            $lng = (float) $dir->drc_longitud;
            // Haversine ≤ 30 km; tiendas sin coordenadas se incluyen como fallback
            $query->where(function ($q) use ($lat, $lng) {
                $q->whereNull('tie_latitud')
                  ->orWhereNull('tie_longitud')
                  ->orWhereRaw(
                      '(6371 * acos(cos(radians(?)) * cos(radians(tie_latitud)) * cos(radians(tie_longitud) - radians(?)) + sin(radians(?)) * sin(radians(tie_latitud)))) <= 30',
                      [$lat, $lng, $lat]
                  );
            });
        } elseif (!empty($dir->drc_ciudad)) {
            $query->where('tie_direccion', 'LIKE', '%' . $dir->drc_ciudad . '%');
        }

        return $query->get();
    }

    public function show($id)
    {
        $tienda = Tienda::with('fachada')->findOrFail($id);

        // Obtener todas las categorías de esta tienda (productos activos)
        $categorias = CategoriaProducto::whereHas('productos', function ($q) use ($id) {
            $q->where('pro_fk_tienda', $id)->where('pro_estado', 1);
        })->get();

        // Categorías seleccionadas desde query parameter
        $categoriasSeleccionadas = [];
        if (request('categorias')) {
            $categoriasSeleccionadas = array_map('intval', explode(',', request('categorias')));
        }

        // Query de productos
        $query = Producto::where('pro_fk_tienda', $id)->where('pro_estado', 1);

        // Filtrar por categorías si hay seleccionadas
        if (!empty($categoriasSeleccionadas)) {
            $query->whereIn('pro_fk_categoria', $categoriasSeleccionadas);
        }

        $productos = $query->with('inventario')->get();

        return view('cliente.tienda', compact('tienda', 'productos', 'categorias', 'categoriasSeleccionadas'));
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

        $esFavorito = false;
        if (auth()->check() && auth()->user()->hasRol('cliente')) {
            $cliente = auth()->user()->cliente;
            if ($cliente) {
                $esFavorito = $cliente->favoritosProductos()
                    ->where('fav_fk_producto', $producto->pro_id)
                    ->exists();
            }
        }

        return view('cliente.producto', compact('producto', 'relacionados', 'esFavorito'));
    }

    function obtenerCarrito($clienteId, $tiendaId)
    {
        return Pedido::where('ped_fk_cliente', $clienteId)
            ->where('ped_fk_tienda', $tiendaId)
            ->where('ped_estado', 'carrito')
            ->first()
            ?? Pedido::forceCreate([
                'ped_fk_cliente'   => $clienteId,
                'ped_fk_tienda'    => $tiendaId,
                'ped_estado'       => 'carrito',
                'ped_codigo'       => uniqid(),
                'ped_fecha_pedido' => now(),
                'ped_total'        => 0,
                'ped_tipo_entrega' => 'domicilio',
            ]);
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
        $clienteId = auth()->user()->cliente?->cli_id;
        $detalle   = DetallePedido::whereHas(
            'pedido',
            fn($q) => $q->where('ped_fk_cliente', $clienteId)->where('ped_estado', 'carrito')
        )->findOrFail($request->detalle_id);

        $detalle->increment('det_cantidad');
        $detalle->update(['det_subtotal' => $detalle->det_cantidad * $detalle->det_precio_unitario]);
        $this->actualizarTotal($detalle->pedido);

        return back();
    }

    // RESTAR
    public function restar(Request $request)
    {
        $clienteId = auth()->user()->cliente?->cli_id;
        $detalle   = DetallePedido::whereHas(
            'pedido',
            fn($q) => $q->where('ped_fk_cliente', $clienteId)->where('ped_estado', 'carrito')
        )->findOrFail($request->detalle_id);

        if ($detalle->det_cantidad > 1) {
            $detalle->decrement('det_cantidad');
            $detalle->update(['det_subtotal' => $detalle->det_cantidad * $detalle->det_precio_unitario]);
        }

        $this->actualizarTotal($detalle->pedido);

        return back();
    }

    // ELIMINAR
    public function eliminar(Request $request)
    {
        $clienteId = auth()->user()->cliente?->cli_id;
        $detalle   = DetallePedido::whereHas(
            'pedido',
            fn($q) => $q->where('ped_fk_cliente', $clienteId)->where('ped_estado', 'carrito')
        )->findOrFail($request->detalle_id);

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

    // FAVORITOS PRODUCTOS
    public function agregarFavoritoProducto(Request $request)
    {
        $user = auth()->user();
        $cliente = $user->cliente;
        if (!$cliente) return response()->json(['success' => false], 403);

        $producto = Producto::findOrFail($request->producto_id);

        Favorito::firstOrCreate([
            'fav_fk_cliente' => $cliente->cli_id,
            'fav_fk_producto' => $producto->pro_id,
        ]);

        return response()->json(['success' => true]);
    }

    public function quitarFavoritoProducto(Request $request)
    {
        $user = auth()->user();
        $cliente = $user->cliente;
        if (!$cliente) return response()->json(['success' => false], 403);

        Favorito::where([
            'fav_fk_cliente' => $cliente->cli_id,
            'fav_fk_producto' => $request->producto_id,
        ])->delete();

        return response()->json(['success' => true]);
    }

    // FAVORITOS TIENDAS
    public function agregarFavoritoTienda(Request $request)
    {
        $user = auth()->user();
        $cliente = $user->cliente;
        if (!$cliente) return response()->json(['success' => false], 403);

        $tienda = Tienda::findOrFail($request->tienda_id);

        Favorito::firstOrCreate([
            'fav_fk_cliente' => $cliente->cli_id,
            'fav_fk_tienda' => $tienda->tie_id,
        ]);

        return response()->json(['success' => true]);
    }

    public function quitarFavoritoTienda(Request $request)
    {
        $user = auth()->user();
        $cliente = $user->cliente;
        if (!$cliente) return response()->json(['success' => false], 403);

        Favorito::where([
            'fav_fk_cliente' => $cliente->cli_id,
            'fav_fk_tienda' => $request->tienda_id,
        ])->delete();

        return response()->json(['success' => true]);
    }

    public function favoritos()
    {
        $user = auth()->user();
        $cliente = $user->cliente;

        $favoritosTiendas = Tienda::whereHas('favoritos', function ($q) use ($cliente) {
            $q->where('fav_fk_cliente', $cliente->cli_id)->whereNotNull('fav_fk_tienda');
        })->with('fachada')->get();

        $favoritosProductos = Producto::whereHas('favoritosDe', function ($q) use ($cliente) {
            $q->where('fav_fk_cliente', $cliente->cli_id)->whereNotNull('fav_fk_producto');
        })->where('pro_estado', 1)->get();

        return view('cliente.favoritos', compact('favoritosTiendas', 'favoritosProductos'));
    }
}
