<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Olvidé mi contraseña — LocalApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: white; display: flex; justify-content: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
        }
        .app { width: 100%; max-width: 430px; min-height: 100vh; background: white; display: flex; flex-direction: column; padding: 0 1.5rem 2rem; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 0 1rem; border-bottom: 1px solid #f0f0f0; margin-bottom: 2rem; }
        .btn-back { display: flex; align-items: center; text-decoration: none; color: #333; }
        .btn-back svg { width: 22px; height: 22px; }
        .header-logo img { height: 36px; }
        .titulo-wrap { margin-bottom: 2rem; }
        .titulo-wrap h1 { font-size: 1.6rem; font-weight: 900; color: #111; margin-bottom: 0.4rem; }
        .titulo-wrap p { font-size: 0.85rem; color: #999; line-height: 1.5; }
        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem; }
        .field input { width: 100%; border: 2px solid #e5e7eb; border-radius: 0.85rem; padding: 0.85rem 1rem; font-family: inherit; font-size: 0.92rem; color: #111; background: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        .field input:focus { border-color: #a8df11; box-shadow: 0 0 0 4px rgba(168,223,17,0.12); }
        .field-error { font-size: 0.75rem; color: #d41b11; margin-top: 0.3rem; font-weight: 600; }
        .status-msg { background: #f0fde0; border: 1px solid #bef264; color: #3f6212; font-size: 0.82rem; font-weight: 600; padding: 0.75rem 1rem; border-radius: 0.75rem; margin-bottom: 1.25rem; line-height: 1.5; }
        .btn-submit { width: 100%; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-family: inherit; font-size: 1rem; font-weight: 800; padding: 0.9rem; border-radius: 999px; border: none; cursor: pointer; box-shadow: 0 6px 20px rgba(168,223,17,0.35); transition: opacity 0.2s, transform 0.15s; margin-bottom: 1.25rem; }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .link-volver { text-align: center; font-size: 0.82rem; color: #999; }
        .link-volver a { color: #111; font-weight: 700; text-decoration: underline; text-underline-offset: 3px; }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <a href="{{ route('cliente.login') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <div class="header-logo">
            <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
        </div>
        <div style="width:22px"></div>
    </div>

    <div class="titulo-wrap">
        <h1>¿Olvidaste tu contraseña?</h1>
        <p>Ingresa tu correo y te enviaremos un enlace para crear una nueva contraseña.</p>
    </div>

    @if (session('status'))
        <div class="status-msg">✓ {{ session('status') }}</div>
    @endif

    @error('email')
        <div class="field-error" style="margin-bottom:1rem;">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('cliente.password.email') }}">
        @csrf

        <div class="field">
            <label>Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="tu@correo.com" required autocomplete="email">
        </div>

        <button type="submit" class="btn-submit">Enviar instrucciones</button>
    </form>

    <p class="link-volver">
        <a href="{{ route('cliente.login') }}">← Volver al inicio de sesión</a>
    </p>

</div>
</body>
</html>
