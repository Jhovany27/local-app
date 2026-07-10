<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>LocalApp — Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: #f4f7f0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            padding: 2rem 1.25rem;
        }

        /* ── HEADER ── */
        .portal-header {
            text-align: center;
            margin-bottom: 2.75rem;
        }

        .logo-wrap {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            margin-bottom: 1rem;
        }

        .logo-wrap img {
            height: 44px;
        }

        .portal-header h1 {
            font-size: 1.75rem;
            font-weight: 900;
            color: #111;
            margin-bottom: 0.35rem;
            letter-spacing: -0.02em;
        }

        .portal-header p {
            font-size: 0.88rem;
            color: #888;
        }

        /* ── CARDS ── */
        .cards {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            width: 100%;
            max-width: 380px;
        }

        @media (min-width: 640px) {
            .cards {
                grid-template-columns: repeat(3, 1fr);
                max-width: 720px;
                gap: 1.25rem;
            }
        }

        .card {
            background: white;
            border-radius: 1.5rem;
            padding: 1.75rem 1.5rem;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 1rem;
            border: 2px solid transparent;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.18s;
        }

        @media (min-width: 640px) {
            .card {
                flex-direction: column;
                align-items: center;
                text-align: center;
                gap: 0.85rem;
                padding: 2rem 1.25rem;
            }
        }

        .card:hover {
            border-color: #a8df11;
            box-shadow: 0 8px 28px rgba(168,223,17,0.18);
            transform: translateY(-3px);
        }

        .card-icon {
            width: 56px;
            height: 56px;
            flex-shrink: 0;
            background: linear-gradient(135deg, #edfab3, #d4f57a);
            border-radius: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-icon svg {
            width: 26px;
            height: 26px;
            color: #4a8a06;
        }

        .card-body { flex: 1; }

        .card-body h2 {
            font-size: 0.95rem;
            font-weight: 800;
            color: #111;
            margin-bottom: 0.2rem;
        }

        .card-body p {
            font-size: 0.75rem;
            color: #999;
            line-height: 1.45;
        }

        /* ── ARROW (mobile only) ── */
        .card-arrow {
            color: #ccc;
            flex-shrink: 0;
        }

        @media (min-width: 640px) {
            .card-arrow { display: none; }
        }

        /* ── FOOTER ── */
        .portal-footer {
            margin-top: 2.5rem;
            font-size: 0.72rem;
            color: #bbb;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="portal-header">
        <div class="logo-wrap">
            <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
        </div>
        <h1>¡Hola, bienvenido!</h1>
        <p>Selecciona tu rol para continuar</p>
    </div>

    <div class="cards">

        {{-- CLIENTE --}}
        <a href="{{ route('cliente.index') }}" class="card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
            </div>
            <div class="card-body">
                <h2>Comprar</h2>
                <p>Descubre tiendas y haz pedidos</p>
            </div>
            <svg class="card-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </a>

        {{-- TIENDA --}}
        <a href="/store/login" class="card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z"/>
                </svg>
            </div>
            <div class="card-body">
                <h2>Tienda</h2>
                <p>Gestiona tu tienda y pedidos</p>
            </div>
            <svg class="card-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </a>

        {{-- REPARTIDOR --}}
        <a href="{{ route('repartidor.login') }}" class="card">
            <div class="card-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/>
                </svg>
            </div>
            <div class="card-body">
                <h2>Repartidor</h2>
                <p>Entrega pedidos en tu zona</p>
            </div>
            <svg class="card-arrow" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
            </svg>
        </a>

    </div>

    <p class="portal-footer">© {{ date('Y') }} LocalApp</p>

</body>
</html>
