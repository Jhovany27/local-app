<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f0f2f0;
            display: flex;
            justify-content: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }

        .app {
            width: 100%;
            max-width: 430px;
            min-height: 100vh;
            background: white;
            display: flex;
            flex-direction: column;
        }

        .header {
            background: #edf3e3;
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header-back {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
        }

        .header-back svg {
            width: 22px;
            height: 22px;
        }

        .header h1 {
            font-size: 1rem;
            font-weight: 800;
            color: #111;
        }

        .body {
            flex: 1;
            padding: 1rem 1.25rem 7rem;
        }

        .tienda-section {
            margin-bottom: 1.5rem;
        }

        .tienda-header {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.75rem;
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 0.85rem 0.85rem 0 0;
        }

        .tienda-img {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            object-fit: cover;
        }

        .tienda-img-empty {
            width: 36px;
            height: 36px;
            border-radius: 0.5rem;
            background: #e8f5d0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .tienda-img-empty svg {
            width: 18px;
            height: 18px;
            color: #a8df11;
        }

        .tienda-nombre {
            font-size: 0.85rem;
            font-weight: 800;
            color: #111;
        }

        .tienda-items {
            font-size: 0.7rem;
            color: #aaa;
        }

        .productos-list {
            border: 1.5px solid #e8f5d0;
            border-top: none;
            overflow: hidden;
        }

        .producto-row {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.85rem;
            border-bottom: 1px solid #f0f9e0;
        }

        .producto-row:last-child {
            border-bottom: none;
        }

        .prod-img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            border-radius: 0.5rem;
            background: #f8f8f8;
            flex-shrink: 0;
        }

        .prod-img-empty {
            width: 52px;
            height: 52px;
            border-radius: 0.5rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .prod-img-empty svg {
            width: 24px;
            height: 24px;
            color: #c6f135;
        }

        .prod-info {
            flex: 1;
            min-width: 0;
        }

        .prod-nombre {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111;
        }

        .prod-precio {
            font-size: 0.75rem;
            color: #888;
            margin-top: 0.1rem;
        }

        .prod-controles {
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .ctrl-btn {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            border: 1.5px solid #e8f5d0;
            background: #f8fdf0;
            color: #4a8a06;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.15s;
        }

        .ctrl-btn:hover {
            background: #a8df11;
            border-color: #a8df11;
            color: white;
        }

        .ctrl-qty {
            font-size: 0.85rem;
            font-weight: 800;
            color: #111;
            min-width: 20px;
            text-align: center;
        }

        .prod-subtotal {
            font-size: 0.85rem;
            font-weight: 800;
            color: #4a8a06;
            min-width: 55px;
            text-align: right;
        }

        .btn-eliminar {
            background: none;
            border: none;
            cursor: pointer;
            color: #ddd;
            padding: 0.2rem;
            transition: color 0.15s;
        }

        .btn-eliminar:hover {
            color: #d41b11;
        }

        .btn-eliminar svg {
            width: 14px;
            height: 14px;
        }

        .tienda-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem;
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-top: none;
            border-radius: 0 0 0.85rem 0.85rem;
        }

        .tienda-total {
            font-size: 0.85rem;
            font-weight: 800;
            color: #111;
        }

        .tienda-total span {
            color: #4a8a06;
        }

        .btn-pedir {
            background: #111;
            color: white;
            font-family: inherit;
            font-size: 0.78rem;
            font-weight: 700;
            padding: 0.55rem 1.1rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
        }

        .btn-pedir:hover {
            background: #a8df11;
            color: #111;
        }

        .btn-pedir svg {
            width: 14px;
            height: 14px;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-icon {
            width: 64px;
            height: 64px;
            border-radius: 1.25rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .empty-icon svg {
            width: 32px;
            height: 32px;
            color: #a8df11;
        }

        .empty-state p {
            font-size: 0.9rem;
            font-weight: 700;
            color: #555;
        }

        .empty-state span {
            font-size: 0.78rem;
            color: #aaa;
        }

        .btn-ir-tiendas {
            background: #a8df11;
            color: #111;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 800;
            padding: 0.75rem 1.5rem;
            border-radius: 999px;
            border: none;
            text-decoration: none;
            margin-top: 0.5rem;
            display: inline-block;
        }

        .success-msg {
            background: #f0fde0;
            border: 1px solid #c6f135;
            color: #4a8a06;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.65rem 1rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            max-width: 430px;
            background: white;
            border-top: 1px solid #f0f0f0;
            display: flex;
            justify-content: space-around;
            padding: 0.85rem 0;
            z-index: 10;
        }

        .nav-item {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #bbb;
            transition: color 0.15s;
        }

        .nav-item.active {
            color: #a8df11;
        }

        .nav-item svg {
            width: 24px;
            height: 24px;
        }
    </style>
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('cliente.index') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h1>Mi carrito</h1>
            <div style="width:22px"></div>
        </div>

        <div class="body">

            @if (session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif

            @php
                $carritoSesion = $carrito ?? [];
                $totalSesion = collect($carritoSesion)->sum('subtotal');
                $hayProductos = $pedidos->isNotEmpty() || !empty($carritoSesion);
            @endphp

            @if (!$hayProductos)
                {{-- VACÍO --}}
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                    </div>
                    <p>Tu carrito está vacío</p>
                    <span>Agrega productos desde las tiendas</span>
                    <a href="{{ route('cliente.index') }}" class="btn-ir-tiendas">Ver tiendas</a>
                </div>
            @else
                {{-- ── CARRITO SIN LOGIN (sesión) ── --}}
                @if (!Auth::check() && !empty($carritoSesion))
                    <div class="tienda-section">
                        <div class="tienda-header">
                            <div class="tienda-img-empty">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                                </svg>
                            </div>
                            <div>
                                <p class="tienda-nombre">Tu carrito</p>
                                <p class="tienda-items">{{ count($carritoSesion) }} producto(s)</p>
                            </div>
                        </div>

                        <div class="productos-list">
                            @foreach ($carritoSesion as $productoId => $item)
                                <div class="producto-row">
                                    @if (!empty($item['foto']))
                                        <img src="{{ asset('storage/' . $item['foto']) }}" class="prod-img">
                                    @else
                                        <div class="prod-img-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="prod-info">
                                        <p class="prod-nombre">{{ $item['nombre'] }}</p>
                                        <p class="prod-precio">${{ number_format($item['precio'], 2) }} c/u</p>
                                    </div>
                                    <div class="prod-controles">
                                        <form method="POST" action="{{ route('carrito.restar') }}">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $productoId }}">
                                            <button type="submit" class="ctrl-btn">−</button>
                                        </form>
                                        <span class="ctrl-qty">{{ $item['cantidad'] }}</span>
                                        <form method="POST" action="{{ route('carrito.sumar') }}">
                                            @csrf
                                            <input type="hidden" name="producto_id" value="{{ $productoId }}">
                                            <button type="submit" class="ctrl-btn">+</button>
                                        </form>
                                    </div>
                                    <span class="prod-subtotal">${{ number_format($item['subtotal'], 2) }}</span>
                                    <form method="POST" action="{{ route('carrito.eliminar') }}">
                                        @csrf
                                        <input type="hidden" name="producto_id" value="{{ $productoId }}">
                                        <button type="submit" class="btn-eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <div class="tienda-footer">
                            <p class="tienda-total">Total: <span>${{ number_format($totalSesion, 2) }}</span></p>
                            {{-- Sin login → pide login al continuar --}}
                            <a href="{{ route('cliente.login', ['redirect' => 'carrito']) }}" class="btn-pedir">
                                Continuar
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endif

                {{-- ── CARRITO CON LOGIN (BD) ── --}}
                @foreach ($pedidos as $pedido)
                    <div class="tienda-section">
                        <div class="tienda-header">
                            @if ($pedido->tienda->fachada?->fac_ruta)
                                <img src="{{ asset('storage/' . $pedido->tienda->fachada->fac_ruta) }}"
                                    class="tienda-img">
                            @else
                                <div class="tienda-img-empty">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18" />
                                    </svg>
                                </div>
                            @endif
                            <div>
                                <p class="tienda-nombre">{{ $pedido->tienda->tie_nombre }}</p>
                                <p class="tienda-items">{{ $pedido->detalles->count() }} producto(s)</p>
                            </div>
                        </div>

                        <div class="productos-list">
                            @foreach ($pedido->detalles as $detalle)
                                <div class="producto-row">
                                    @if ($detalle->producto?->foto_principal)
                                        <img src="{{ asset('storage/' . $detalle->producto->foto_principal) }}"
                                            class="prod-img">
                                    @else
                                        <div class="prod-img-empty">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909" />
                                            </svg>
                                        </div>
                                    @endif
                                    <div class="prod-info">
                                        <p class="prod-nombre">{{ $detalle->producto?->pro_nombre }}</p>
                                        <p class="prod-precio">${{ number_format($detalle->det_precio_unitario, 2) }}
                                            c/u</p>
                                    </div>
                                    <div class="prod-controles">
                                        <form method="POST" action="{{ route('carrito.restar') }}">
                                            @csrf
                                            <input type="hidden" name="detalle_id" value="{{ $detalle->det_id }}">
                                            <button type="submit" class="ctrl-btn">−</button>
                                        </form>
                                        <span class="ctrl-qty">{{ $detalle->det_cantidad }}</span>
                                        <form method="POST" action="{{ route('carrito.sumar') }}">
                                            @csrf
                                            <input type="hidden" name="detalle_id" value="{{ $detalle->det_id }}">
                                            <button type="submit" class="ctrl-btn">+</button>
                                        </form>
                                    </div>
                                    <span class="prod-subtotal">${{ number_format($detalle->det_subtotal, 2) }}</span>
                                    <form method="POST" action="{{ route('carrito.eliminar') }}">
                                        @csrf
                                        <input type="hidden" name="detalle_id" value="{{ $detalle->det_id }}">
                                        <button type="submit" class="btn-eliminar">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>

                        <div class="tienda-footer">
                            <p class="tienda-total">Total: <span>${{ number_format($pedido->ped_total, 2) }}</span>
                            </p>
                            <a href="{{ route('carrito.checkout', $pedido->ped_id) }}" class="btn-pedir">
                                Continuar
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach

            @endif

        </div>

        <nav class="bottom-nav">
            <a href="{{ route('cliente.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
            <a href="{{ route('carrito.index') }}" class="nav-item active">
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
</body>

</html>
