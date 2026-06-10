<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Favoritos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/favoritos.css')
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ route('cliente.index') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="header-center">
                <h1>Favoritos</h1>
            </div>
            <div style="width: 36px;"></div>
        </div>

        {{-- BODY --}}
        <div class="body">

            {{-- FILTROS --}}
            <div class="filtros-wrap">
                <button class="filtro-btn active" data-filtro="todos">Todos</button>
                <button class="filtro-btn" data-filtro="tiendas">Tiendas</button>
                <button class="filtro-btn" data-filtro="productos">Productos</button>
            </div>

            {{-- TIENDAS FAVORITAS --}}
            <div class="seccion" data-seccion="tiendas">
                <h2 class="seccion-titulo">Tiendas Favoritas</h2>

                @if ($favoritosTiendas->count() > 0)
                    <div class="tienda-list">
                        @foreach ($favoritosTiendas as $tienda)
                            <a href="{{ route('cliente.tienda', $tienda->tie_id) }}" class="tienda-card">

                                @if ($tienda->fachada?->fac_ruta)
                                    <img src="{{ asset('storage/' . $tienda->fachada->fac_ruta) }}"
                                        alt="{{ $tienda->tie_nombre }}" class="tienda-img">
                                @else
                                    <div class="tienda-img-empty">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349" />
                                        </svg>
                                    </div>
                                @endif

                                <div class="tienda-info">
                                    <p class="tienda-nombre">{{ $tienda->tie_nombre }}</p>
                                    <p class="tienda-dir">{{ $tienda->tie_direccion }}</p>
                                </div>

                                <div class="tienda-arrow">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </div>

                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.312-2.733C5.099 3.75 3 5.765 3 8.25c0 7.22 9 13 9 13s9-5.78 9-13Z" />
                            </svg>
                        </div>
                        <p>No tienes tiendas favoritas</p>
                        <span>Agrega tiendas a favoritos desde el inicio</span>
                    </div>
                @endif
            </div>

            {{-- PRODUCTOS FAVORITOS --}}
            <div class="seccion" data-seccion="productos">
                <h2 class="seccion-titulo">Productos Favoritos</h2>

                @if ($favoritosProductos->count() > 0)
                    <div class="productos-grid">
                        @foreach ($favoritosProductos as $producto)
                            <div class="producto-wrapper">
                                <a href="{{ route('cliente.producto', $producto->pro_id) }}" class="producto-item">

                                    @if ($producto->foto_principal)
                                        <img src="{{ asset('storage/' . $producto->foto_principal) }}"
                                            alt="{{ $producto->pro_nombre }}" class="producto-img">
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

                                <button class="favorito-btn" onclick="toggleFavoritoProducto(event, {{ $producto->pro_id }})">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.312-2.733C5.099 3.75 3 5.765 3 8.25c0 7.22 9 13 9 13s9-5.78 9-13Z" />
                                    </svg>
                                </button>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.312-2.733C5.099 3.75 3 5.765 3 8.25c0 7.22 9 13 9 13s9-5.78 9-13Z" />
                            </svg>
                        </div>
                        <p>No tienes productos favoritos</p>
                        <span>Agrega productos a favoritos desde las tiendas</span>
                    </div>
                @endif
            </div>

        </div>

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
            @auth
                <a href="{{ route('cliente.perfil') }}" class="nav-item active">
                @else
                    <a href="{{ route('cliente.login') }}" class="nav-item active">
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
        let filtroActual = 'todos';

        // Filtros
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filtroActual = this.dataset.filtro;

                document.querySelectorAll('.seccion').forEach(seccion => {
                    const tipo = seccion.dataset.seccion;
                    const mostrar = filtroActual === 'todos' || filtroActual === tipo;
                    seccion.style.display = mostrar ? 'block' : 'none';
                });
            });
        });

        // Toggle favorito producto
        function toggleFavoritoProducto(event, productoId) {
            event.preventDefault();
            event.stopPropagation();

            const wrapper = event.target.closest('.producto-wrapper');
            const esFavorito = wrapper.dataset.favorito === 'true';
            const url = '{{ route("cliente.favorito.producto.quitar") }}';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ producto_id: productoId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    wrapper.style.display = 'none';
                }
            });
        }
    </script>
</body>

</html>
