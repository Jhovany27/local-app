<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Solicitud en revisión</title>
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
            align-items: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            padding: 1.5rem;
        }

        .app {
            width: 100%;
            max-width: 430px;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
            padding: 2.5rem 2rem;
        }

        .icon-wrap {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(168, 223, 17, 0.35);
        }

        .icon-wrap svg {
            width: 36px;
            height: 36px;
            color: white;
        }

        h1 {
            font-size: 1.4rem;
            font-weight: 900;
            color: #111;
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 0.82rem;
            color: #aaa;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.5;
        }

        .pasos {
            display: flex;
            flex-direction: column;
            gap: 0.85rem;
            margin-bottom: 2rem;
        }

        .paso {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .paso-circle {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .paso-circle.done {
            background: #a8df11;
        }

        .paso-circle.done svg {
            width: 14px;
            height: 14px;
            color: white;
        }

        .paso-circle.pending {
            background: #fff7e0;
            border: 2px solid #fcd34d;
        }

        .paso-circle.pending svg {
            width: 14px;
            height: 14px;
            color: #f59e0b;
        }

        .paso-circle.waiting {
            background: #f0f0f0;
            border: 2px dashed #ddd;
        }

        .paso-circle span {
            font-size: 0.65rem;
            font-weight: 800;
            color: #aaa;
        }

        .paso-texto p:first-child {
            font-size: 0.85rem;
            font-weight: 700;
            color: #111;
        }

        .paso-texto p.done-text {
            text-decoration: line-through;
            color: #aaa;
        }

        .paso-texto p:last-child {
            font-size: 0.7rem;
            color: #aaa;
            margin-top: 0.1rem;
        }

        .btn-logout {
            width: 100%;
            background: transparent;
            color: #d41b11;
            font-family: inherit;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.75rem;
            border-radius: 999px;
            border: 2px solid #fca5a530;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 0.5rem;
        }

        .btn-logout:hover {
            background: #fff1f0;
            border-color: #d41b11;
        }

        /* Rechazado */
        .icon-wrap-danger {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            border-radius: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 8px 24px rgba(239,68,68,0.3);
        }

        .icon-wrap-danger svg {
            width: 36px;
            height: 36px;
            color: white;
        }

        .motivo-box {
            background: #fff1f0;
            border: 1.5px solid #fca5a5;
            border-radius: 0.85rem;
            padding: 1rem 1.25rem;
            margin-bottom: 1.5rem;
        }

        .motivo-label {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #d41b11;
            margin-bottom: 0.4rem;
        }

        .motivo-texto {
            font-size: 0.85rem;
            color: #333;
            line-height: 1.6;
        }

        .btn-reenviar {
            display: block;
            width: 100%;
            text-align: center;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 6px 20px rgba(168,223,17,0.35);
            margin-bottom: 0.75rem;
            transition: opacity 0.2s;
        }

        .btn-reenviar:hover { opacity: 0.9; }
    </style>
</head>

<body>
    <div class="app">

        @if (session('info') || session('success'))
        <div style="background:#eff6ff;border:1.5px solid #93c5fd;border-radius:0.85rem;padding:0.85rem 1rem;margin-bottom:1.5rem;font-size:0.8rem;color:#1d4ed8;line-height:1.5;text-align:center;">
            {{ session('info') ?? session('success') }}
        </div>
        @endif

        @php $rechazado = $repartidor && (int)$repartidor->rep_estado === 2; @endphp

        @if ($rechazado)
            {{-- ── ESTADO: RECHAZADO ── --}}
            <div class="icon-wrap-danger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>

            <h1 style="color:#d41b11;">Solicitud rechazada</h1>
            <p class="subtitle" style="margin-bottom:1.5rem;">Tu solicitud fue revisada y no fue aprobada. Lee el motivo y corrige tu información.</p>

            <div class="motivo-box">
                <p class="motivo-label">Motivo del rechazo</p>
                <p class="motivo-texto">{{ $repartidor->rep_motivo_rechazo ?? 'Sin motivo especificado.' }}</p>
            </div>

            <a href="{{ route('repartidor.completar-perfil') }}" class="btn-reenviar">
                Corregir y reenviar solicitud
            </a>

        @else
            {{-- ── ESTADO: EN REVISIÓN ── --}}
            <div class="icon-wrap">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                </svg>
            </div>

            <h1>Solicitud en revisión</h1>
            <p class="subtitle">Recibimos tu documentación. Un administrador la revisará y activará tu cuenta.</p>

            <div class="pasos">
                <div class="paso">
                    <div class="paso-circle done">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <div class="paso-texto">
                        <p class="done-text">Registro completado</p>
                    </div>
                </div>

                <div class="paso">
                    <div class="paso-circle done">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <div class="paso-texto">
                        <p class="done-text">Documentos enviados</p>
                    </div>
                </div>

                <div class="paso">
                    <div class="paso-circle pending">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6l4 2m6-2a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z" />
                        </svg>
                    </div>
                    <div class="paso-texto">
                        <p>Revisión por el administrador</p>
                        <p>En proceso...</p>
                    </div>
                </div>

                <div class="paso">
                    <div class="paso-circle waiting">
                        <span>4</span>
                    </div>
                    <div class="paso-texto">
                        <p style="color:#aaa">Activación de tu cuenta</p>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('repartidor.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>

    </div>
</body>

</html>
