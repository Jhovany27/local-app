<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/tienda.css')
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <div>
                <a href="{{ route('cliente.index') }}" class="header-back">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </a>
            </div>
            <div class="header-center">
                <h1>{{ $tienda->tie_nombre }}</h1>
                <p>{{ $tienda->tie_direccion }}</p>
            </div>
            <a href="{{ route('carrito.index') }}" class="header-cart">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                @php $total = collect(session('carrito', []))->sum('cantidad'); @endphp
                @if ($total > 0)
                    <span class="cart-badge">{{ $total }}</span>
                @endif
            </a>
        </div>

        {{-- DIRECCIÓN --}}
        @php
            $dirUrl =
                Auth::check() && Auth::user()->hasRol('cliente')
                    ? route('cliente.direcciones')
                    : route('cliente.login') . '?redirect=direcciones';

            $dirActiva =
                Auth::check() && session('direccion_id') ? \App\Models\Direccion::find(session('direccion_id')) : null;
        @endphp

        <a href="{{ $dirUrl }}" class="direccion">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>
            <div class="direccion-texto">
                @if ($dirActiva)
                    <p>Dirección de entrega</p>
                    <p>{{ $dirActiva->drc_calle }}, {{ $dirActiva->drc_ciudad }}</p>
                @else
                    <p>Dirección de entrega</p>
                    <p>{{ Auth::check() ? 'Toca para agregar' : 'Inicia sesión para continuar' }}</p>
                @endif
            </div>
            <div class="direccion-arrow">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </a>

        {{-- BUSCADOR --}}
        <div class="search-wrap">
            <div class="search-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                <input type="text" id="buscador" placeholder="Buscar productos...">
            </div>
        </div>

        {{-- CATEGORÍAS --}}
        <div class="categorias-wrap">
            <div class="categorias-header">
                <h2>Categorías</h2>
                <span>Ver todas</span>
            </div>
            <div class="categorias-scroll">
                <div class="cat-chip active">Todas</div>
                <div class="cat-chip">Alcohol</div>
                <div class="cat-chip">Frutas</div>
                <div class="cat-chip">Verduras</div>
                <div class="cat-chip">Frituras</div>
                <div class="cat-chip">Lácteos</div>
                <div class="cat-chip">Bebidas</div>
            </div>
        </div>

        {{-- FILTROS --}}
        <div class="filtros-wrap">
            <button class="filtro-btn active">Todos</button>
            <button class="filtro-btn">♥ Favoritos</button>
        </div>

        {{-- PRODUCTOS --}}
        <div class="productos-grid">
            @foreach ($productos as $producto)
                <a href="{{ route('cliente.producto', $producto->pro_id) }}" class="producto-item">

                    <span class="favorito-btn">♥</span>

                    @if ($producto->foto_principal)
                        <img src="{{ asset('storage/' . $producto->foto_principal) }}" alt="{{ $producto->pro_nombre }}"
                            class="producto-img">
                    @else
                        <div class="producto-img-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                            </svg>
                        </div>
                    @endif

                    <p class="nombre">{{ $producto->pro_nombre }}</p>
                    <p class="marca">{{ $producto->pro_marca }}</p>

                    <div class="producto-footer">
                        <span class="precio">${{ number_format($producto->pro_precio_venta, 2) }}</span>
                        <form action="{{ route('carrito.agregar', $producto->pro_id) }}" method="POST"
                            onclick="event.stopPropagation()">
                            @csrf
                            <button type="submit" class="btn-add">+</button>
                        </form>
                    </div>

                </a>
            @endforeach
        </div>

        {{-- BOTTOM NAV --}}
        <nav class="bottom-nav">
            <a href="{{ route('cliente.index') }}" class="nav-item active">
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
            @auth
                <a href="{{ route('cliente.perfil') }}" class="nav-item">
                @else
                    <a href="{{ route('cliente.login') }}" class="nav-item">
                    @endauth
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                </a>
        </nav>

    </div>

    <script>
        const buscador = document.getElementById('buscador');
        buscador.addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll('.producto-item').forEach(item => {
                const nombre = item.querySelector('.nombre').textContent.toLowerCase();
                item.style.display = nombre.includes(filtro) ? 'block' : 'none';
            });
        });
    </script>
</body>

</html>
