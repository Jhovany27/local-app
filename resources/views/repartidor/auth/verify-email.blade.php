<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verifica tu correo — Repartidor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f0f2f0; display: flex; justify-content: center; align-items: center; font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; min-height: 100vh; padding: 1.5rem; }
        .app { width: 100%; max-width: 430px; background: white; border-radius: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); padding: 2.5rem 2rem; text-align: center; }
        .app-icon { width: 72px; height: 72px; background: #f0fde0; border: 2px solid #d4f0a0; border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .app-icon svg { width: 36px; height: 36px; color: #4a8a06; }
        h1 { font-size: 1.4rem; font-weight: 900; color: #111; margin-bottom: 0.4rem; }
        .subtitle { font-size: 0.82rem; color: #aaa; margin-bottom: 1.75rem; }
        p.desc { font-size: 0.88rem; color: #555; line-height: 1.65; margin-bottom: 1.5rem; }
        p.desc strong { color: #111; }
        .msg-ok { background: #f0fde0; border: 1px solid #c6f0a0; color: #3a7a05; font-size: 0.82rem; font-weight: 600; padding: 0.65rem 1rem; border-radius: 0.75rem; margin-bottom: 1.25rem; }
        .btn { display: block; width: 100%; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-family: inherit; font-size: 0.95rem; font-weight: 800; padding: 0.85rem; border-radius: 999px; border: none; cursor: pointer; box-shadow: 0 6px 20px rgba(168,223,17,0.3); transition: opacity 0.2s; margin-bottom: 0.75rem; }
        .btn:hover { opacity: 0.9; }
        .link-btn { background: none; border: none; font-family: inherit; font-size: 0.8rem; color: #aaa; cursor: pointer; text-decoration: none; }
        .link-btn:hover { color: #d41b11; }
    </style>
</head>
<body>
<div class="app">

    <div class="app-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
        </svg>
    </div>

    <h1>Verifica tu correo</h1>
    <p class="subtitle">Panel de repartidores · LocalApp</p>

    <p class="desc">
        Te enviamos un enlace de verificación a<br>
        <strong>{{ Auth::user()->email }}</strong>.<br>
        Revisa tu bandeja de entrada o spam.
    </p>

    @if (session('message'))
        <div class="msg-ok">{{ session('message') }}</div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn">Reenviar correo de verificación</button>
    </form>

    <form method="POST" action="{{ route('repartidor.logout') }}">
        @csrf
        <button type="submit" class="link-btn">Cerrar sesión</button>
    </form>

</div>
</body>
</html>
