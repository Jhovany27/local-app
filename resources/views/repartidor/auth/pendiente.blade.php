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
    </style>
</head>

<body>
    <div class="app">

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

        <form method="POST" action="{{ route('repartidor.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Cerrar sesión</button>
        </form>

    </div>
</body>

</html>
