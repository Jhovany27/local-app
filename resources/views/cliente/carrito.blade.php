<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/carrito.css')
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
