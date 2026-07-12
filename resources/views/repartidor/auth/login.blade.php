<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Repartidor — Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f0f2f0; display: flex; justify-content: center; align-items: center; font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; min-height: 100vh; padding: 1.5rem; }
        .app { width: 100%; max-width: 430px; background: white; border-radius: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); padding: 2.5rem 2rem; }

        .app-icon { width: 64px; height: 64px; background: #f0fde0; border: 2px solid #d4f0a0; border-radius: 1.25rem; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.25rem; }
        .app-icon svg { width: 32px; height: 32px; color: #4a8a06; }

        h1 { font-size: 1.5rem; font-weight: 900; color: #111; text-align: center; margin-bottom: 0.35rem; }
        .subtitle { font-size: 0.8rem; color: #aaa; text-align: center; margin-bottom: 2rem; }

        .error-msg { background: #fff1f0; border: 1px solid #fca5a5; color: #d41b11; font-size: 0.8rem; font-weight: 600; padding: 0.65rem 1rem; border-radius: 0.75rem; margin-bottom: 1rem; }

        .field { margin-bottom: 1rem; }
        .field label { display: block; font-size: 0.8rem; font-weight: 600; color: #333; margin-bottom: 0.4rem; }
        .field input { width: 100%; border: 2px solid #e5e7eb; border-radius: 0.85rem; padding: 0.75rem 1rem; font-family: inherit; font-size: 0.9rem; color: #111; outline: none; transition: border-color 0.2s; }
        .field input:focus { border-color: #a8df11; box-shadow: 0 0 0 4px rgba(168,223,17,0.1); }

        .btn-submit { width: 100%; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-family: inherit; font-size: 1rem; font-weight: 800; padding: 0.9rem; border-radius: 999px; border: none; cursor: pointer; box-shadow: 0 6px 20px rgba(168,223,17,0.35); transition: opacity 0.2s; margin-top: 0.5rem; }
        .btn-submit:hover { opacity: 0.9; }

        .footer-links { text-align: center; margin-top: 1.5rem; font-size: 0.82rem; color: #aaa; }
        .footer-links a { color: #4a8a06; font-weight: 700; text-decoration: none; }
        .footer-links a:hover { text-decoration: underline; }

        .portal-link { display: block; text-align: center; margin-top: 1rem; font-size: 0.72rem; color: #bbb; text-decoration: none; }
        .portal-link:hover { color: #888; }
    </style>
</head>
<body>
<div class="app">

    <div class="app-icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25"/>
        </svg>
    </div>

    <h1>Bienvenido</h1>
    <p class="subtitle">Panel de repartidores · LocalApp</p>

    @if($errors->any())
    <div class="error-msg">
        @if($errors->first() === 'verificar_correo')
            Debes <a href="{{ route('verificar-correo') }}" style="color:#d41b11;font-weight:800;text-decoration:underline;">verificar tu correo</a> antes de iniciar sesión.
        @else
            {{ $errors->first() }}
        @endif
    </div>
    @endif

    <form method="POST" action="{{ route('repartidor.login.store') }}">
        @csrf
        <div class="field">
            <label>Correo</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="correo@ejemplo.com" required>
        </div>
        <div class="field">
            <label>Contraseña</label>
            <input type="password" name="password"
                   placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn-submit">Entrar</button>
    </form>

    <p class="footer-links">
        ¿No tienes cuenta?
        <a href="{{ route('repartidor.registro') }}">Regístrate</a>
    </p>

    <a href="{{ route('portal') }}" class="portal-link">← Volver al portal</a>

</div>
</body>
</html>