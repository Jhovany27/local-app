<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        body {
            background: #f0f2f0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: "Instrument Sans", sans-serif;
        }

        .card {
            background: white;
            border-radius: 1.5rem;
            padding: 2.5rem 2rem;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .icon {
            width: 64px;
            height: 64px;
            background: #f0fde0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }

        .icon svg {
            width: 32px;
            height: 32px;
            color: #4a8a06;
        }

        h1 {
            font-size: 1.2rem;
            font-weight: 900;
            color: #111;
            margin-bottom: 0.5rem;
        }

        p {
            font-size: 0.85rem;
            color: #888;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .btn {
            display: block;
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 800;
            padding: 0.85rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            margin-bottom: 0.75rem;
        }

        .link {
            font-size: 0.78rem;
            color: #7ab80e;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="card">
        <div class="icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
            </svg>
        </div>
        <h1>Verifica tu correo</h1>
        <p>Te enviamos un enlace de verificación a <strong>{{ Auth::user()->email }}</strong>. Revisa tu bandeja de
            entrada.</p>

        @if (session('message'))
            <p style="color:#4a8a06;font-weight:600;font-size:0.82rem;margin-bottom:1rem;">{{ session('message') }}</p>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn">Reenviar correo</button>
        </form>

        <form method="POST" action="{{ route('cliente.logout') }}">
            @csrf
            <button type="submit" style="background:none;border:none;cursor:pointer;" class="link">Cerrar
                sesión</button>
        </form>
    </div>
</body>

</html>
