<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>{{ $producto->pro_nombre }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/producto.css')
</head>

<body>

    <div class="app">
        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('cliente.tienda', $producto->pro_fk_tienda) }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>

            </a>

            <div class="header-center">
                <h1>{{ $producto->tienda->tie_nombre }}</h1>
                <p>{{ $producto->tienda->tie_direccion }}</p>
            </div>

            <a href="{{ route('carrito.index') }}" class="header-cart">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                @php
                    if (Auth::check() && Auth::user()->hasRol('cliente')) {
                        $cliente = Auth::user()->cliente;
                        $total = $cliente
                            ? \App\Models\Pedido::where('ped_fk_cliente', $cliente->cli_id)
                                ->where('ped_estado', 'carrito')
                                ->withCount('detalles')
                                ->get()
                                ->sum('detalles_count')
                            : 0;
                    } else {
                        $total = collect(session('carrito', []))->sum('cantidad');
                    }
                @endphp
                @if ($total > 0)
                    <span class="cart-badge">{{ $total }}</span>
                @endif
            </a>
        </div>

        {{-- IMAGEN --}}
        <div class="producto-image-wrap">
            <span class="cat-badge">{{ $producto->categoria_producto?->cat_nombre ?? 'Producto' }}</span>

            @if ($producto->foto_principal)
                <img src="{{ asset('storage/' . $producto->foto_principal) }}" alt="{{ $producto->pro_nombre }}">
            @else
                <div class="producto-no-img">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
            @endif
        </div>

        {{-- INFO --}}
        <div class="producto-info">
            <h1 class="producto-nombre">{{ $producto->pro_nombre }}</h1>
            <p class="producto-precio">${{ number_format($producto->pro_precio_venta, 2) }}</p>
            <p class="producto-marca">{{ $producto->pro_marca }}</p>

            @php
                $stock = $producto->inventario?->inv_stock_actual ?? 0;
                $minimo = $producto->inventario?->inv_stock_minimo ?? 0;
            @endphp

            @if ($stock <= 0)
                <span class="stock-badge stock-out"><span class="stock-dot"></span>Sin stock</span>
            @elseif($stock <= $minimo)
                <span class="stock-badge stock-low"><span class="stock-dot"></span>Stock bajo — {{ $stock }}
                    disponibles</span>
            @else
                <span class="stock-badge stock-ok"><span class="stock-dot"></span>En stock — {{ $stock }}
                    disponibles</span>
            @endif
        </div>

        {{-- ACCIONES --}}
        <div class="acciones">
            <button class="btn-lista {{ $esFavorito ? 'btn-lista--activo' : '' }}"
                id="btn-lista"
                data-favorito="{{ $esFavorito ? 'true' : 'false' }}"
                data-producto-id="{{ $producto->pro_id }}"
                @guest onclick="window.location='{{ route('cliente.login') }}'" @endguest
                @auth onclick="toggleFavoritoDetalle(this)" @endauth>
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="{{ $esFavorito ? 'currentColor' : 'none' }}"
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                </svg>
                <span>{{ $esFavorito ? 'En tu lista' : 'Agregar a lista' }}</span>
            </button>

            @if ($stock > 0)
                <form action="{{ route('carrito.agregar', $producto->pro_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-agregar">Agregar</button>
                </form>
            @else
                <button class="btn-agregar" disabled style="opacity:0.5;cursor:not-allowed;">Sin stock</button>
            @endif
        </div>

        {{-- DETALLES --}}
        <div class="seccion">
            <p class="seccion-titulo">Detalles del producto</p>
            <p class="detalles-texto">{{ $producto->pro_detalles }}</p>
        </div>

        <div class="divider"></div>

        {{-- RELACIONADOS --}}
        @if ($relacionados->isNotEmpty())
            <div class="seccion">
                <p class="seccion-titulo">Complemento para tus compras</p>
            </div>

            <div class="relacionados-scroll">
                @foreach ($relacionados as $rel)
                    <a href="{{ route('cliente.producto', $rel->pro_id) }}" class="rel-card">
                        <span class="rel-fav">♥</span>

                        @if ($rel->foto_principal)
                            <img src="{{ asset('storage/' . $rel->foto_principal) }}" alt="{{ $rel->pro_nombre }}"
                                class="rel-img">
                        @else
                            <div class="rel-img"
                                style="background:#f0fde0;border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.2" stroke="#a8df11" width="32" height="32">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                                </svg>
                            </div>
                        @endif

                        <p class="rel-nombre">{{ $rel->pro_nombre }}</p>
                        <p class="rel-marca">{{ $rel->pro_marca }}</p>

                        <div class="rel-footer">
                            <span class="rel-precio">${{ number_format($rel->pro_precio_venta, 2) }}</span>
                            <form action="{{ route('carrito.agregar', $rel->pro_id) }}" method="POST">
                                @csrf
                                <button type="submit" class="rel-btn">+</button>
                            </form>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

        <div class="pb-nav"></div>

        {{-- BOTTOM NAV --}}
        <nav class="bottom-nav">
            <a href="{{ route('cliente.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
            <a href="{{ route('carrito.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
            </a>
            <a href="{{ route('cliente.pedidos') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            <a href="{{ route('cliente.perfil') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </a>
        </nav>

    </div>

    <script>
        function toggleFavoritoDetalle(btn) {
            const esFavorito = btn.dataset.favorito === 'true';
            const productoId = btn.dataset.productoId;
            const url = esFavorito
                ? '{{ route("cliente.favorito.producto.quitar") }}'
                : '{{ route("cliente.favorito.producto.agregar") }}';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ producto_id: productoId }),
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success) return;

                const ahora = !esFavorito;
                btn.dataset.favorito = ahora ? 'true' : 'false';
                btn.classList.toggle('btn-lista--activo', ahora);

                const svg = btn.querySelector('svg');
                svg.setAttribute('fill', ahora ? 'currentColor' : 'none');

                btn.querySelector('span').textContent = ahora ? 'En tu lista' : 'Agregar a lista';
            });
        }
    </script>

</body>

</html>
