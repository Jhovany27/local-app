<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mi perfil</title>
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
            padding: 1.25rem;
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

        .hero {
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            padding: 2rem 1.5rem 3rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            position: relative;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            right: 0;
            height: 24px;
            background: white;
            border-radius: 24px 24px 0 0;
        }

        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            border: 3px solid rgba(255, 255, 255, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .avatar svg {
            width: 36px;
            height: 36px;
            color: white;
        }

        .hero-nombre {
            font-size: 1.1rem;
            font-weight: 900;
            color: white;
        }

        .hero-email {
            font-size: 0.78rem;
            color: rgba(255, 255, 255, 0.8);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(255, 255, 255, 0.25);
            color: white;
            font-size: 0.68rem;
            font-weight: 700;
            padding: 0.2rem 0.75rem;
            border-radius: 999px;
        }

        .body {
            flex: 1;
            padding: 1.5rem 1.25rem 7rem;
        }

        .seccion-titulo {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #a8df11;
            margin-bottom: 0.75rem;
            padding-left: 0.25rem;
        }

        .seccion {
            margin-bottom: 1.5rem;
        }

        .info-card {
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            overflow: hidden;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f5f5f5;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 34px;
            height: 34px;
            border-radius: 0.65rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon svg {
            width: 16px;
            height: 16px;
            color: #4a8a06;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 0.65rem;
            font-weight: 700;
            color: #bbb;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.1rem;
        }

        .info-value {
            font-size: 0.88rem;
            font-weight: 600;
            color: #111;
        }

        .acciones-card {
            background: white;
            border: 1.5px solid #e8f5d0;
            border-radius: 1rem;
            overflow: hidden;
        }

        .accion-row {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #f5f5f5;
            text-decoration: none;
            cursor: pointer;
        }

        .accion-row:last-child {
            border-bottom: none;
        }

        .accion-row:hover {
            background: #fafff5;
        }

        .accion-icon {
            width: 34px;
            height: 34px;
            border-radius: 0.65rem;
            background: #f0fde0;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .accion-icon svg {
            width: 16px;
            height: 16px;
            color: #4a8a06;
        }

        .accion-icon.danger {
            background: #fff1f0;
        }

        .accion-icon.danger svg {
            color: #d41b11;
        }

        .accion-label {
            flex: 1;
            font-size: 0.88rem;
            font-weight: 600;
            color: #111;
        }

        .accion-label.danger {
            color: #d41b11;
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
            <a href="{{ route('repartidor.index') }}" class="btn-back">
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

        {{-- HERO --}}
        <div class="hero">
            <div class="avatar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </div>
            <p class="hero-nombre">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</p>
            <p class="hero-email">{{ $user->email }}</p>
            <span class="hero-badge">🏍 {{ $repartidor->rep_tipo_vehiculo }}</span>
        </div>

        <div class="body">

            {{-- DATOS --}}
            <div class="seccion">
                <p class="seccion-titulo">Datos personales</p>
                <div class="info-card">
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Nombre completo</p>
                            <p class="info-value">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Correo</p>
                            <p class="info-value">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Teléfono</p>
                            <p class="info-value">{{ $persona?->per_telefono ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                            </svg>
                        </div>
                        <div class="info-content">
                            <p class="info-label">Vehículo</p>
                            <p class="info-value">{{ $repartidor->rep_tipo_vehiculo }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACCIONES --}}
            <div class="seccion">
                <p class="seccion-titulo">Mi cuenta</p>
                <div class="acciones-card">
                    <form method="POST" action="{{ route('repartidor.logout') }}">
                        @csrf
                        <button type="submit" class="accion-row"
                            style="width:100%;background:none;border:none;text-align:left;">
                            <div class="accion-icon danger">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                                </svg>
                            </div>
                            <span class="accion-label danger">Cerrar sesión</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <nav class="bottom-nav">
            <a href="{{ route('repartidor.index') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                </svg>
            </a>
            <a href="{{ route('repartidor.historial') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            <a href="{{ route('repartidor.perfil') }}" class="nav-item active">
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
