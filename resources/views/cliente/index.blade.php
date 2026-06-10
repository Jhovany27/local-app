<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Tiendas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/index.css')
</head>

<body>
    <div class="app">

        {{-- HEADER --}}
        <div class="header">
            <div class="header-top">
                <h1>Elige una tienda</h1>
                <span class="header-ayuda">Ayuda</span>
            </div>
            <div class="search-inner">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z" />
                </svg>
                <input type="text" id="buscador" placeholder="Buscar tiendas...">
            </div>
        </div>

        {{-- BODY --}}
        <div class="body">

            {{-- UBICACIÓN — visible siempre, comportamiento según sesión --}}
            @auth
                @if (Auth::user()->hasRol('cliente'))
                    @php
                        $dirActiva = session('direccion_id')
                            ? \App\Models\Direccion::find(session('direccion_id'))
                            : null;
                    @endphp
                    <a href="{{ route('cliente.direcciones') }}" class="ubicacion">
                        <div class="ubicacion-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <div class="ubicacion-texto">
                            @if ($dirActiva)
                                <p>Dirección de entrega</p>
                                <p>{{ $dirActiva->drc_calle }}, {{ $dirActiva->drc_ciudad }}</p>
                            @else
                                <p>Registra o usa una dirección</p>
                                <p>Toca para agregar</p>
                            @endif
                        </div>
                        <div class="ubicacion-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </a>
                @endif
            @else
                {{-- Sin sesión → botón que lleva al login --}}
                <a href="{{ route('cliente.login') }}?redirect=direcciones" class="ubicacion">
                    <div class="ubicacion-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </div>
                    <div class="ubicacion-texto">
                        <p>Registra o usa una dirección</p>
                        <p>Inicia sesión para continuar</p>
                    </div>
                    <div class="ubicacion-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </div>
                </a>
            @endauth

            {{-- FILTROS — siempre visibles --}}
            <div class="filtros">
                <button class="filtro-btn active" data-filtro="todas">Todas</button>
                <button class="filtro-btn" data-filtro="favoritas">★ Favoritas</button>
            </div>

            {{-- LISTA — siempre visible --}}
            @php
                $dirActiva = session('direccion_id') ? \App\Models\Direccion::find(session('direccion_id')) : null;
            @endphp
            <p class="seccion-label">
                @if ($dirActiva && $dirActiva->drc_ciudad)
                    Tiendas en {{ $dirActiva->drc_ciudad }}
                @else
                    Tiendas cercanas
                @endif
            </p>

            <div class="tienda-list">
                @forelse($tiendas as $tienda)
                    <div class="tienda-card-wrapper" data-tienda-id="{{ $tienda->tie_id }}" data-favorito="{{ auth()->check() && auth()->user()->hasRol('cliente') && auth()->user()->cliente->favoritosTiendas()->where('fav_fk_tienda', $tienda->tie_id)->exists() ? 'true' : 'false' }}">
                        <a href="{{ route('cliente.tienda', $tienda->tie_id) }}" class="tienda-card">

                        @if ($tienda->fachada?->fac_ruta)
                            <img src="{{ asset('storage/' . $tienda->fachada->fac_ruta) }}"
                                alt="{{ $tienda->tie_nombre }}" class="tienda-img">
                        @else
                            <div class="tienda-img-empty">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                </svg>
                            </div>
                        @endif

                        <div class="tienda-info">
                            <p class="tienda-nombre">{{ $tienda->tie_nombre }}</p>
                            <p class="tienda-dir">{{ $tienda->tie_direccion }}</p>
                            <span class="tienda-badge">
                                <span class="tienda-badge-dot"></span>
                                Abierta
                            </span>
                        </div>

                    </a>

                    <button class="btn-favorito" onclick="toggleFavoritoTienda(event, {{ $tienda->tie_id }})">
                        <svg class="heart-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.312-2.733C5.099 3.75 3 5.765 3 8.25c0 7.22 9 13 9 13s9-5.78 9-13Z" />
                        </svg>
                    </button>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615" />
                            </svg>
                        </div>
                        <p>No hay tiendas disponibles</p>
                        <span>Intenta con otra dirección o vuelve más tarde</span>
                    </div>
                @endforelse
            </div>

        </div>

        {{-- BOTTOM NAV — siempre visible --}}
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

    {{-- BOTTOM SHEET: seleccionar dirección --}}
    @if (!empty($sinDireccion) && $sinDireccion)
    <style>
        .dir-overlay {
            position: fixed; inset: 0; z-index: 100;
            background: rgba(0,0,0,0.45);
            display: flex; align-items: flex-end;
        }
        .dir-sheet {
            width: 100%; max-width: 480px; margin: 0 auto;
            background: white;
            border-radius: 1.5rem 1.5rem 0 0;
            padding: 1rem 1.25rem 2rem;
            max-height: 80vh; overflow-y: auto;
        }
        .dir-handle {
            width: 40px; height: 4px; background: #e0e0e0;
            border-radius: 99px; margin: 0 auto 1.25rem;
        }
        .dir-title {
            font-size: 1.1rem; font-weight: 900; color: #111;
            margin-bottom: 0.3rem;
        }
        .dir-subtitle {
            font-size: 0.78rem; color: #aaa; margin-bottom: 1.25rem; line-height: 1.5;
        }
        .dir-item {
            display: flex; align-items: center; gap: 0.85rem;
            width: 100%; background: none; border: 1.5px solid #e5e7eb;
            border-radius: 0.85rem; padding: 0.85rem 1rem;
            margin-bottom: 0.65rem; cursor: pointer;
            text-align: left; transition: border-color 0.2s;
        }
        .dir-item:hover { border-color: #a8df11; }
        .dir-icon {
            width: 36px; height: 36px; border-radius: 0.6rem;
            background: #f0fde0; display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .dir-icon svg { width: 16px; height: 16px; color: #4a8a06; }
        .dir-name { font-size: 0.85rem; font-weight: 700; color: #111; }
        .dir-addr { font-size: 0.72rem; color: #aaa; margin-top: 0.1rem; }
        .dir-empty {
            text-align: center; color: #aaa; font-size: 0.82rem;
            padding: 1.5rem 0; margin-bottom: 0.5rem;
        }
        .btn-agregar-dir {
            display: block; width: 100%; text-align: center;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a; font-weight: 800; font-size: 0.9rem;
            padding: 0.85rem; border-radius: 999px;
            text-decoration: none; margin-bottom: 0.75rem;
            box-shadow: 0 6px 20px rgba(168,223,17,0.3);
        }
        .btn-sin-dir {
            display: block; width: 100%; text-align: center;
            font-size: 0.78rem; color: #aaa; text-decoration: none;
            padding: 0.5rem;
        }
        .btn-sin-dir:hover { color: #555; }
    </style>

    <div class="dir-overlay" id="dirOverlay">
        <div class="dir-sheet">
            <div class="dir-handle"></div>
            <p class="dir-title">¿Dónde te entregamos?</p>
            <p class="dir-subtitle">Selecciona una dirección para ver tiendas disponibles en tu área.</p>

            @forelse ($direcciones as $dir)
                <form method="POST" action="{{ route('cliente.direcciones.seleccionar', $dir->drc_id) }}">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ route('cliente.index') }}">
                    <button type="submit" class="dir-item">
                        <div class="dir-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="dir-name">{{ $dir->drc_calle }}{{ $dir->drc_numero ? ' ' . $dir->drc_numero : '' }}</p>
                            <p class="dir-addr">{{ $dir->drc_colonia ? $dir->drc_colonia . ', ' : '' }}{{ $dir->drc_ciudad }}</p>
                        </div>
                    </button>
                </form>
            @empty
                <p class="dir-empty">Aún no tienes direcciones guardadas.</p>
            @endforelse

            <a href="{{ route('cliente.direcciones.create') }}" class="btn-agregar-dir">
                + Agregar dirección
            </a>
            <a href="{{ route('cliente.index', ['sin_filtro' => 1]) }}" class="btn-sin-dir">
                Explorar sin dirección
            </a>
        </div>
    </div>
    @endif

    <script>
        let filtroActual = 'todas';

        // Buscar tiendas
        document.getElementById('buscador').addEventListener('keyup', function() {
            const filtro = this.value.toLowerCase();
            document.querySelectorAll('.tienda-card-wrapper').forEach(wrapper => {
                const card = wrapper.querySelector('.tienda-card');
                const nombre = card.querySelector('.tienda-nombre').textContent.toLowerCase();
                const coincide = nombre.includes(filtro);
                const esVisible = filtroActual === 'todas' ? true : (wrapper.dataset.favorito === 'true');
                wrapper.style.display = coincide && esVisible ? '' : 'none';
            });
        });

        // Filtros
        document.querySelectorAll('.filtro-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filtro-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                filtroActual = this.dataset.filtro;

                const busqueda = document.getElementById('buscador').value.toLowerCase();
                document.querySelectorAll('.tienda-card-wrapper').forEach(wrapper => {
                    const nombre = wrapper.querySelector('.tienda-nombre').textContent.toLowerCase();
                    const coincide = nombre.includes(busqueda);
                    const esVisible = filtroActual === 'todas' ? true : (wrapper.dataset.favorito === 'true');
                    wrapper.style.display = coincide && esVisible ? '' : 'none';
                });
            });
        });

        // Toggle favorito tienda
        function toggleFavoritoTienda(event, tiendaId) {
            event.preventDefault();
            event.stopPropagation();

            const wrapper = event.target.closest('.tienda-card-wrapper');
            const esFavorito = wrapper.dataset.favorito === 'true';
            const url = esFavorito
                ? '{{ route("cliente.favorito.tienda.quitar") }}'
                : '{{ route("cliente.favorito.tienda.agregar") }}';

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ tienda_id: tiendaId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    wrapper.dataset.favorito = esFavorito ? 'false' : 'true';
                    const icon = wrapper.querySelector('.heart-icon').closest('.btn-favorito');
                    icon.classList.toggle('favorito-activo');
                }
            });
        }
    </script>
</body>

</html>
