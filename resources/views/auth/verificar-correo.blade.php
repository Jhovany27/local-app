<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Verificar correo — LocalApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: #f5f7f0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif;
            min-height: 100vh;
            padding: 1.5rem;
        }
        .card {
            width: 100%;
            max-width: 440px;
            background: white;
            border-radius: 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08);
            padding: 2.5rem 2rem;
        }
        .icon {
            width: 64px; height: 64px;
            background: #f0fde0;
            border: 2px solid #d4f0a0;
            border-radius: 1.25rem;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.25rem;
        }
        .icon svg { width: 32px; height: 32px; color: #4a8a06; }
        h1 { font-size: 1.45rem; font-weight: 900; color: #111; text-align: center; margin-bottom: 0.4rem; }
        .subtitle { font-size: 0.82rem; color: #aaa; text-align: center; margin-bottom: 1.75rem; }

        .email-box {
            background: #f5f7f0;
            border: 2px solid #e2edd0;
            border-radius: 1rem;
            padding: 0.9rem 1.1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .email-box svg { width: 20px; height: 20px; color: #4a8a06; flex-shrink: 0; }
        .email-box span { font-size: 0.9rem; font-weight: 700; color: #111; word-break: break-all; }

        .alert-success {
            background: #f0fde0; border: 1px solid #c6e89a; color: #3a7005;
            font-size: 0.82rem; font-weight: 600; padding: 0.75rem 1rem;
            border-radius: 0.85rem; margin-bottom: 1.25rem;
        }
        .alert-error {
            background: #fff1f0; border: 1px solid #fca5a5; color: #d41b11;
            font-size: 0.82rem; font-weight: 600; padding: 0.75rem 1rem;
            border-radius: 0.85rem; margin-bottom: 1.25rem;
        }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a; font-family: inherit; font-size: 0.95rem;
            font-weight: 800; padding: 0.85rem; border-radius: 999px;
            border: none; cursor: pointer;
            box-shadow: 0 6px 20px rgba(168,223,17,0.35);
            transition: opacity 0.2s;
        }
        .btn-primary:hover { opacity: 0.9; }

        .divider {
            display: flex; align-items: center; gap: 0.75rem;
            margin: 1.5rem 0; color: #ccc; font-size: 0.78rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px; background: #f0f0f0;
        }

        .section-title {
            font-size: 0.82rem; font-weight: 700; color: #555;
            margin-bottom: 0.75rem;
        }
        .field { margin-bottom: 1rem; }
        .field label { display: block; font-size: 0.8rem; font-weight: 600; color: #333; margin-bottom: 0.4rem; }
        .field input {
            width: 100%; border: 2px solid #e5e7eb; border-radius: 0.85rem;
            padding: 0.75rem 1rem; font-family: inherit; font-size: 0.9rem;
            color: #111; outline: none; transition: border-color 0.2s;
        }
        .field input:focus { border-color: #a8df11; box-shadow: 0 0 0 4px rgba(168,223,17,0.1); }
        .field-error { font-size: 0.75rem; color: #d41b11; margin-top: 0.3rem; }

        .btn-secondary {
            width: 100%;
            background: white; color: #111; font-family: inherit; font-size: 0.9rem;
            font-weight: 700; padding: 0.8rem; border-radius: 999px;
            border: 2px solid #e5e7eb; cursor: pointer;
            transition: border-color 0.2s;
        }
        .btn-secondary:hover { border-color: #a8df11; }

        .back-link {
            display: block; text-align: center; margin-top: 1.5rem;
            font-size: 0.8rem; color: #aaa; text-decoration: none;
        }
        .back-link:hover { color: #555; }
    </style>
</head>
<body>
<div class="card">

    <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
        </svg>
    </div>

    <h1>Verifica tu correo</h1>
    <p class="subtitle">Te enviamos un enlace de activación a tu cuenta</p>

    {{-- ALERTAS --}}
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- EMAIL ACTUAL --}}
    <div class="email-box">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0Zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 1 0-2.636 6.364M16.5 12V8.25"/>
        </svg>
        <span>{{ $user->email }}</span>
    </div>

    {{-- REENVIAR --}}
    <form method="POST" action="{{ route('verificar-correo.reenviar') }}">
        @csrf
        <button type="submit" class="btn-primary">Reenviar correo de verificación</button>
    </form>

    <div class="divider">¿Te equivocaste de correo?</div>

    {{-- CAMBIAR EMAIL --}}
    <p class="section-title">Actualizar correo electrónico</p>
    <form method="POST" action="{{ route('verificar-correo.actualizar') }}">
        @csrf
        <div class="field">
            <label>Nuevo correo</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="nuevo@correo.com" required>
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>
        <button type="submit" class="btn-secondary">Actualizar y reenviar verificación</button>
    </form>

    {{-- VOLVER --}}
    @php
        $loginRoute = match($rol) {
            'tienda'      => url('/store/login'),
            'repartidor'  => route('repartidor.login'),
            default       => route('cliente.login'),
        };
    @endphp
    <a href="{{ $loginRoute }}" class="back-link">← Volver al inicio de sesión</a>

</div>
</body>
</html>
