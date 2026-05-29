<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis pedidos</title>
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
            padding: 1.25rem 1.25rem 1rem;
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

        .header-logo img {
            height: 36px;
        }

        .body {
            flex: 1;
            padding: 1.25rem 1.25rem 7rem;
        }

        .page-title {
            font-size: 1.3rem;
            font-weight: 900;
            color: #111;
            margin-bottom: 1.25rem;
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

        /* Pedido card */
        .pedido-card {
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            overflow: hidden;
            margin-bottom: 1rem;
        }

        .pedido-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.85rem 1rem;
            background: #f8fdf0;
        }

        .pedido-tienda {
            font-size: 0.85rem;
            font-weight: 800;
            color: #111;
        }

        .pedido-fecha {
            font-size: 0.68rem;
            color: #aaa;
            margin-top: 0.1rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.25rem 0.65rem;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .badge-pendiente {
            background: #fff7e0;
            color: #92400e;
            border: 1px solid #fcd34d;
        }

        .badge-preparacion {
            background: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #93c5fd;
        }

        .badge-listo {
            background: #f0fde0;
            color: #4a8a06;
            border: 1px solid #a8df11;
        }

        .badge-completado {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-cancelado {
            background: #fff1f0;
            color: #d41b11;
            border: 1px solid #fca5a5;
        }

        .pedido-productos {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f0f9e0;
        }

        .prod-mini {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.35rem;
        }

        .prod-mini:last-child {
            margin-bottom: 0;
        }

        .prod-mini-img {
            width: 28px;
            height: 28px;
            border-radius: 0.35rem;
            object-fit: contain;
            background: #f8f8f8;
            flex-shrink: 0;
        }

        .prod-mini-nombre {
            font-size: 0.75rem;
            color: #555;
            flex: 1;
        }

        .prod-mini-qty {
            font-size: 0.72rem;
            font-weight: 700;
            color: #111;
        }

        .pedido-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
        }

        .pedido-total {
            font-size: 0.85rem;
            font-weight: 800;
            color: #4a8a06;
        }

        .pedido-tipo {
            font-size: 0.7rem;
            color: #aaa;
        }

        /* Empty */
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

        .btn-ir {
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

        /* nav */
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
            <div class="header-logo">
                <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
            </div>
            <div style="width:22px"></div>
        </div>

        <div class="body">

            <h1 class="page-title">Mis pedidos</h1>

            @if (session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif

            @forelse($pedidos as $pedido)
                <div class="pedido-card">

                    {{-- Header --}}
                    <div class="pedido-header">
                        <div>
                            <p class="pedido-tienda">{{ $pedido->tienda->tie_nombre }}</p>
                            <p class="pedido-fecha">{{ $pedido->ped_fecha_pedido->format('d/m/Y H:i') }}</p>
                        </div>
                        @php
                            $badgeClass = match ($pedido->ped_estado) {
                                'pendiente' => 'badge-pendiente',
                                'en_preparacion' => 'badge-preparacion',
                                'listo' => 'badge-listo',
                                'completado' => 'badge-completado',
                                'cancelado' => 'badge-cancelado',
                                default => 'badge-pendiente',
                            };
                            $badgeLabel = match ($pedido->ped_estado) {
                                'pendiente' => 'Pendiente',
                                'en_preparacion' => 'En preparación',
                                'listo' => 'Listo',
                                'completado' => 'Completado',
                                'cancelado' => 'Cancelado',
                                default => $pedido->ped_estado,
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }}">{{ $badgeLabel }}</span>
                    </div>

                    {{-- Productos (max 3) --}}
                    <div class="pedido-productos">
                        @foreach ($pedido->detalles->take(3) as $det)
                            <div class="prod-mini">
                                @if ($det->producto?->foto_principal)
                                    <img src="{{ asset('storage/' . $det->producto->foto_principal) }}"
                                        class="prod-mini-img">
                                @else
                                    <div class="prod-mini-img" style="background:#f0fde0;"></div>
                                @endif
                                <span class="prod-mini-nombre">{{ $det->producto?->pro_nombre }}</span>
                                <span class="prod-mini-qty">×{{ $det->det_cantidad }}</span>
                            </div>
                        @endforeach
                        @if ($pedido->detalles->count() > 3)
                            <p style="font-size:0.7rem;color:#aaa;margin-top:0.25rem;">
                                +{{ $pedido->detalles->count() - 3 }} producto(s) más
                            </p>
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="pedido-footer">
                        <span class="pedido-total">${{ number_format($pedido->ped_total, 2) }}</span>
                        <span class="pedido-tipo">
                            {{ $pedido->ped_tipo_entrega === 'domicilio' ? '🛵 Envío a domicilio' : '🏪 Recoger en tienda' }}
                        </span>
                    </div>

                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                        </svg>
                    </div>
                    <p>Sin pedidos aún</p>
                    <span>Haz tu primer pedido desde las tiendas</span>
                    <a href="{{ route('cliente.index') }}" class="btn-ir">Ver tiendas</a>
                </div>
            @endforelse

        </div>

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
            <a href="{{ route('cliente.pedidos') }}" class="nav-item active">
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
