<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis direcciones</title>
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
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.25rem 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .btn-back {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
        }

        .btn-back svg {
            width: 22px;
            height: 22px;
        }

        .header-logo img {
            height: 36px;
        }

        .body {
            flex: 1;
            padding: 1.5rem 1.25rem 7rem;
        }

        .page-title {
            font-size: 1.5rem;
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

        .dir-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-bottom: 1.5rem;
        }

        /* Card clickeable */
        .dir-card {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            padding: 1rem;
            text-decoration: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .dir-card:hover {
            border-color: #a8df11;
            box-shadow: 0 4px 16px rgba(168, 223, 17, 0.15);
        }

        .dir-card.activa {
            border-color: #a8df11;
            background: #f8fdf0;
            box-shadow: 0 4px 16px rgba(168, 223, 17, 0.15);
        }

        .dir-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f0fde0;
            border: 1.5px solid #d4f0a0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .dir-icon svg {
            width: 18px;
            height: 18px;
            color: #a8df11;
        }

        .dir-info {
            flex: 1;
            min-width: 0;
        }

        .dir-calle {
            font-size: 0.82rem;
            font-weight: 700;
            color: #111;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dir-detalle {
            font-size: 0.72rem;
            color: #999;
            margin-top: 0.15rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dir-activa-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: #a8df11;
            color: white;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.15rem 0.6rem;
            border-radius: 999px;
            margin-top: 0.3rem;
        }

        .dir-arrow {
            flex-shrink: 0;
        }

        .dir-arrow svg {
            width: 16px;
            height: 16px;
            color: #ccc;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            padding: 3rem 1.5rem;
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

        .btn-agregar {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 800;
            padding: 0.9rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(168, 223, 17, 0.35);
            transition: opacity 0.2s, transform 0.15s;
        }

        .btn-agregar:hover {
            opacity: 0.9;
            transform: translateY(-1px);
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

        {{-- HEADER --}}
        <div class="header">
            <a href="{{ request()->query('redirect') ?? route('cliente.index') }}" class="btn-back">
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

        {{-- BODY --}}
        <div class="body">

            <h1 class="page-title">Direcciones</h1>

            @if (session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif

            @php $direccionActiva = session('direccion_id'); @endphp

            <div class="dir-list">
                @forelse($direcciones as $dir)
                    {{-- Card clickeable que lleva al detalle --}}
                    <a href="{{ route('cliente.direcciones.show', ['id' => $dir->drc_id, 'redirect' => request()->query('redirect')]) }}"
                        class="dir-card {{ $direccionActiva == $dir->drc_id ? 'activa' : '' }}">

                        <div class="dir-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>

                        <div class="dir-info">
                            <p class="dir-calle">
                                {{ $dir->drc_calle }}{{ $dir->drc_numero ? ' #' . $dir->drc_numero : '' }},
                                {{ $dir->drc_ciudad }}, {{ $dir->drc_estado }}
                            </p>
                            <p class="dir-detalle">CP
                                {{ $dir->drc_codigo_postal }}{{ $dir->drc_colonia ? ' · ' . $dir->drc_colonia : '' }}
                            </p>
                            @if ($direccionActiva == $dir->drc_id)
                                <span class="dir-activa-badge">✓ Usando esta</span>
                            @endif
                        </div>

                        <div class="dir-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>

                    </a>

                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                        </div>
                        <p>Sin direcciones guardadas</p>
                        <span>Agrega tu primera dirección de entrega</span>
                    </div>
                @endforelse
            </div>

            <a href="{{ route('cliente.direcciones.create') }}" class="btn-agregar">
                Agregar ubicación
            </a>

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
            <a href="#" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
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
