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

        {{-- BANNER ESTADO DE LA TIENDA --}}
        @php $tiendaAbierta = $tienda->estaAbierta(); @endphp
        @if(!$tiendaAbierta)
            <div style="background:#fff7ed;border-bottom:1.5px solid #fed7aa;padding:0.65rem 1.25rem;display:flex;align-items:center;gap:0.6rem;font-size:0.78rem;color:#c2410c;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:17px;height:17px;flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z"/>
                </svg>
                <span>
                    <strong>Tienda cerrada</strong>
                    @if($tienda->horarioTexto())
                        · Horario: {{ $tienda->horarioTexto() }}
                    @endif
                    · Puedes explorar los productos pero no podrás comprar ahora.
                </span>
            </div>
        @endif

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
        @if ($categorias->count() > 0)
            <div class="categorias-wrap">
                <div class="categorias-header">
                    <h2>Filtrar por categoría</h2>
                    <button class="btn-ver-todas" onclick="abrirModalCategorias()">
                        Ver todas
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="width:16px;height:16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18" />
                        </svg>
                    </button>
                </div>
                <div class="categorias-scroll">
                    {{-- "Ver todas" (sin filtro) --}}
                    <a href="{{ route('cliente.tienda', $tienda->tie_id) }}" class="cat-chip {{ empty($categoriasSeleccionadas) ? 'active' : '' }}">
                        Todas
                    </a>

                    {{-- Primeras 5 categorías dinámicas (las más importantes) --}}
                    @foreach ($categorias->take(5) as $cat)
                        @php
                            $isSelected = in_array($cat->cat_id, $categoriasSeleccionadas);
                            if ($isSelected) {
                                $nuevas = array_diff($categoriasSeleccionadas, [$cat->cat_id]);
                            } else {
                                $nuevas = array_merge($categoriasSeleccionadas, [$cat->cat_id]);
                            }
                            $url = !empty($nuevas)
                                ? route('cliente.tienda', $tienda->tie_id) . '?categorias=' . implode(',', $nuevas)
                                : route('cliente.tienda', $tienda->tie_id);
                        @endphp
                        <a href="{{ $url }}" class="cat-chip {{ $isSelected ? 'active' : '' }}">
                            {{ $cat->cat_nombre }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- MODAL DE CATEGORÍAS --}}
            <div id="modalCategorias" class="modal-categorias hidden">
                <div class="modal-categorias-overlay" onclick="cerrarModalCategorias()"></div>
                <div class="modal-categorias-content">
                    <div class="modal-categorias-header">
                        <h3>Selecciona categorías</h3>
                        <button class="modal-categorias-close" onclick="cerrarModalCategorias()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:24px;height:24px;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="modal-categorias-list">
                        @foreach ($categorias as $cat)
                            @php
                                $isSelected = in_array($cat->cat_id, $categoriasSeleccionadas);
                            @endphp
                            <label class="categoria-checkbox">
                                <input type="checkbox" name="categorias[]" value="{{ $cat->cat_id }}"
                                    {{ $isSelected ? 'checked' : '' }}
                                    onchange="actualizarCheckboxes()">
                                <span class="checkbox-visual"></span>
                                <span class="categoria-nombre">{{ $cat->cat_nombre }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="modal-categorias-footer">
                        <button class="btn-limpiar" onclick="limpiarFiltros()">Limpiar</button>
                        <button class="btn-aplicar" onclick="aplicarFiltros()">Aplicar</button>
                    </div>
                </div>
            </div>
        @endif

        {{-- FILTROS --}}
        <div class="filtros-wrap">
            <button class="filtro-btn active" data-filtro="todos">Todos</button>
            <button class="filtro-btn" data-filtro="favoritos">♥ Favoritos</button>
        </div>

        {{-- PRODUCTOS --}}
        <div class="productos-grid">
            @if ($productos->count() > 0)
                @foreach ($productos as $producto)
                    <div class="producto-wrapper" data-producto-id="{{ $producto->pro_id }}" data-favorito="{{ auth()->check() && auth()->user()->hasRol('cliente') && auth()->user()->cliente && auth()->user()->cliente->favoritosProductos()->where('fav_fk_producto', $producto->pro_id)->exists() ? 'true' : 'false' }}">
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
                                @php $sinStock = !$producto->inventario || $producto->inventario->inv_stock_actual <= 0; @endphp
                                @if($sinStock)
                                    <button type="button" class="btn-add btn-add--agotado" disabled
                                        onclick="event.stopPropagation()" title="Sin stock">
                                        —
                                    </button>
                                @elseif(!$tiendaAbierta)
                                    <button type="button" class="btn-add btn-add--agotado" disabled
                                        onclick="event.stopPropagation()" title="Tienda cerrada"
                                        style="background:#e5e7eb;color:#9ca3af;">
                                        —
                                    </button>
                                @else
                                    <form action="{{ route('carrito.agregar', $producto->pro_id) }}" method="POST"
                                        onclick="event.stopPropagation()">
                                        @csrf
                                        <button type="submit" class="btn-add">+</button>
                                    </form>
                                @endif
                            </div>

                        </a>

                        <button class="favorito-btn" onclick="toggleFavoritoProducto(event, {{ $producto->pro_id }})">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.312-2.733C5.099 3.75 3 5.765 3 8.25c0 7.22 9 13 9 13s9-5.78 9-13Z" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            @else
                <div style="grid-column: 1 / -1; text-align: center; padding: 3rem 1rem; color: #999;">
                    <p style="font-size: 0.95rem;">No hay productos en {{ !empty($categoriasSeleccionadas) ? 'estas categorías' : 'esta tienda' }}</p>
                </div>
            @endif
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

        // Modal de categorías
        function abrirModalCategorias() {
            document.getElementById('modalCategorias').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModalCategorias() {
            document.getElementById('modalCategorias').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function limpiarFiltros() {
            document.querySelectorAll('.categoria-checkbox input').forEach(cb => {
                cb.checked = false;
            });
        }

        function aplicarFiltros() {
            const checkboxes = document.querySelectorAll('.categoria-checkbox input:checked');
            const categorias = Array.from(checkboxes).map(cb => cb.value);

            if (categorias.length === 0) {
                window.location.href = '{{ route('cliente.tienda', $tienda->tie_id) }}';
            } else {
                window.location.href = '{{ route('cliente.tienda', $tienda->tie_id) }}?categorias=' + categorias.join(',');
            }
        }

        function actualizarCheckboxes() {
            // Opcional: para actualizaciones en tiempo real
        }

        // Cerrar modal al hacer clic fuera
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('modalCategorias');
            const content = document.querySelector('.modal-categorias-content');
            if (modal && event.target === modal && !content.contains(event.target)) {
                cerrarModalCategorias();
            }
        });

        let filtroProductoActual = 'todos';

        // Filtros de productos
        document.querySelectorAll('.filtros-wrap .filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtros-wrap .filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filtroProductoActual = this.dataset.filtro;

                document.querySelectorAll('.producto-wrapper').forEach(wrapper => {
                    const esVisible = filtroProductoActual === 'todos' ? true : (wrapper.dataset.favorito === 'true');
                    wrapper.style.display = esVisible ? 'block' : 'none';
                });
            });
        });

        // Toggle favorito producto
        function toggleFavoritoProducto(event, productoId) {
            event.preventDefault();
            event.stopPropagation();

            const wrapper = event.target.closest('.producto-wrapper');
            const esFavorito = wrapper.dataset.favorito === 'true';
            const url = esFavorito
                ? '{{ route("cliente.favorito.producto.quitar") }}'
                : '{{ route("cliente.favorito.producto.agregar") }}';

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
                    wrapper.dataset.favorito = esFavorito ? 'false' : 'true';
                    const icon = wrapper.querySelector('.favorito-btn svg');
                    icon.classList.toggle('favorito-activo');
                }
            });
        }
    </script>

    <style>
        .btn-ver-todas {
            background: none;
            border: none;
            color: #a8df11;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .modal-categorias {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.3);
        }

        .modal-categorias.hidden {
            display: none;
        }

        .modal-categorias-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: transparent;
        }

        .modal-categorias-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 500px;
            border-radius: 1rem;
            padding: 2rem;
            max-height: 85vh;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: fadeInScale 0.3s ease-out;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-categorias-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eee;
        }

        .modal-categorias-header h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: #111;
            margin: 0;
        }

        .modal-categorias-close {
            background: none;
            border: none;
            cursor: pointer;
            color: #999;
            padding: 0.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .modal-categorias-close:hover {
            color: #333;
        }

        .modal-categorias-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .categoria-checkbox {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
            padding: 0.75rem;
            border-radius: 0.5rem;
            transition: background 0.2s;
        }

        .categoria-checkbox:hover {
            background: #f5f5f5;
        }

        .categoria-checkbox input {
            display: none;
        }

        .checkbox-visual {
            width: 1.25rem;
            height: 1.25rem;
            border: 2px solid #d1d5db;
            border-radius: 0.35rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .categoria-checkbox input:checked + .checkbox-visual {
            background: #a8df11;
            border-color: #a8df11;
        }

        .categoria-checkbox input:checked + .checkbox-visual::after {
            content: '✓';
            color: white;
            font-weight: bold;
            font-size: 0.85rem;
        }

        .categoria-nombre {
            font-size: 0.95rem;
            color: #333;
            font-weight: 500;
        }

        .modal-categorias-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .btn-limpiar, .btn-aplicar {
            padding: 0.85rem;
            border: none;
            border-radius: 0.75rem;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-limpiar {
            background: #f0f0f0;
            color: #666;
        }

        .btn-limpiar:hover {
            background: #e0e0e0;
        }

        .btn-aplicar {
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            box-shadow: 0 4px 15px rgba(168, 223, 17, 0.3);
        }

        .btn-aplicar:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.4);
        }

        @media (max-width: 640px) {
            .modal-categorias-list {
                grid-template-columns: 1fr;
            }

            .modal-categorias-content {
                width: 95%;
                padding: 1.5rem;
            }
        }
    </style>

    <script>
        (function () {
            function animarAlCarrito(btn, form) {
                if (btn.dataset.animando === '1') return;
                btn.dataset.animando = '1';

                var cartEl = document.querySelector('.header-cart');
                var btnRect = btn.getBoundingClientRect();

                btn.style.transition = 'transform 0.15s ease-out, background 0.15s, color 0.15s';
                btn.style.transform  = 'scale(1.4)';
                btn.style.background = '#a8df11';
                btn.style.color      = '#111';
                setTimeout(function () { btn.style.transform = 'scale(1)'; }, 150);

                if (!cartEl) {
                    setTimeout(function () { form.submit(); }, 300);
                    return;
                }

                var cartRect = cartEl.getBoundingClientRect();
                var dot = document.createElement('span');
                dot.style.cssText =
                    'position:fixed;border-radius:50%;background:#a8df11;z-index:9999;pointer-events:none;' +
                    'box-shadow:0 0 0 5px rgba(168,223,17,0.28);' +
                    'left:' + (btnRect.left + btnRect.width  / 2 - 11) + 'px;' +
                    'top:'  + (btnRect.top  + btnRect.height / 2 - 11) + 'px;' +
                    'width:22px;height:22px;';
                document.body.appendChild(dot);

                void dot.offsetWidth;

                var destX = cartRect.left + cartRect.width  / 2 - 6;
                var destY = cartRect.top  + cartRect.height / 2 - 6;
                dot.style.transition = 'left 0.48s cubic-bezier(0.2,0.8,0.4,1),top 0.48s cubic-bezier(0.2,0.8,0.4,1),width 0.48s,height 0.48s,opacity 0.48s,box-shadow 0.48s';
                dot.style.left      = destX + 'px';
                dot.style.top       = destY + 'px';
                dot.style.width     = '12px';
                dot.style.height    = '12px';
                dot.style.opacity   = '0.2';
                dot.style.boxShadow = 'none';

                setTimeout(function () {
                    dot.remove();
                    cartEl.style.transition = 'transform 0.13s ease-out';
                    cartEl.style.transform  = 'scale(1.5)';
                    setTimeout(function () {
                        cartEl.style.transform = 'scale(0.9)';
                        setTimeout(function () {
                            cartEl.style.transform = '';
                            form.submit();
                        }, 110);
                    }, 130);
                }, 490);
            }

            document.querySelectorAll('form').forEach(function (form) {
                if (!form.querySelector('.btn-add')) return;
                form.addEventListener('submit', function (e) {
                    var btn = form.querySelector('button[type="submit"]');
                    if (!btn || btn.disabled || btn.dataset.animando === '1') return;
                    e.preventDefault();
                    animarAlCarrito(btn, form);
                });
            });
        })();
    </script>
</body>

</html>
