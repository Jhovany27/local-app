<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis direcciones</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/direcciones/index.css')

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
