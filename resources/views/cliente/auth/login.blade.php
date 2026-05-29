<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión — LocalApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: white;
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
            padding: 0 1.5rem 2rem;
        }

        /* ── HEADER ── */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 0 1rem;
            border-bottom: 1px solid #f0f0f0;
            margin-bottom: 2rem;
        }

        .btn-back {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
        }

        .btn-back svg { width: 22px; height: 22px; }

        .header-logo img { height: 36px; }

        /* ── TÍTULO ── */
        .titulo-wrap {
            margin-bottom: 2rem;
        }

        .titulo-wrap h1 {
            font-size: 1.6rem;
            font-weight: 900;
            color: #111;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.4rem;
        }

        .titulo-wrap h1 svg { width: 24px; height: 24px; color: #a8df11; }

        .titulo-wrap p { font-size: 0.85rem; color: #999; }

        .titulo-wrap a {
            color: #111;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* ── CAMPOS ── */
        .field { margin-bottom: 1.25rem; }

        .field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .field-wrap { position: relative; }

        .field input {
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 0.85rem;
            padding: 0.85rem 1rem;
            font-family: inherit;
            font-size: 0.92rem;
            color: #111;
            background: white;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field input:focus {
            border-color: #a8df11;
            box-shadow: 0 0 0 4px rgba(168,223,17,0.12);
        }

        .field input.has-toggle { padding-right: 3rem; }

        .toggle-pwd {
            position: absolute;
            right: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #aaa;
            display: flex;
            align-items: center;
            transition: color 0.15s;
        }

        .toggle-pwd:hover { color: #4a8a06; }
        .toggle-pwd svg { width: 18px; height: 18px; }

        /* ── ERROR ── */
        .error-msg {
            background: #fff1f0;
            border: 1px solid #fca5a5;
            color: #d41b11;
            font-size: 0.78rem;
            font-weight: 600;
            padding: 0.6rem 0.9rem;
            border-radius: 0.75rem;
            margin-bottom: 1rem;
        }

        /* ── BOTÓN SUBMIT ── */
        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #a8df11, #7cc10a);
            color: #1a1a1a;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 800;
            padding: 0.9rem;
            border-radius: 999px;
            border: none;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(168,223,17,0.35);
            transition: opacity 0.2s, transform 0.15s;
            margin-bottom: 1.25rem;
        }

        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

        /* ── OLVIDÉ CONTRASEÑA ── */
        .olvide {
            text-align: center;
            font-size: 0.82rem;
            color: #999;
            text-decoration: underline;
            text-underline-offset: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="app">

    {{-- HEADER --}}
    <div class="header">
        <a href="{{ route('cliente.index') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <div class="header-logo">
            <img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp">
        </div>
        <div style="width:22px"></div>{{-- spacer --}}
    </div>

    {{-- TÍTULO --}}
    <div class="titulo-wrap">
        <h1>
            Iniciar sesión
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
            </svg>
        </h1>
        <p>¿No tienes cuenta? <a href="{{ route('cliente.registro') }}">Crea tu cuenta</a></p>
    </div>

    {{-- ERRORES --}}
    @error('email')
    <div class="error-msg">{{ $message }}</div>
    @enderror

    {{-- FORM --}}
    <form method="POST" action="{{ route('cliente.login.store') }}">
        @csrf

        <div class="field">
            <label>Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="tu@correo.com" required autocomplete="email">
        </div>

        <div class="field">
            <label>Contraseña</label>
            <div class="field-wrap">
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required class="has-toggle"
                       autocomplete="current-password">
                <button type="button" class="toggle-pwd" onclick="togglePwd('password','eyeOpen','eyeClosed')">
                    <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M9.88 5.458A9.77 9.77 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a16.708 16.708 0 01-4.293 5.774M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit">Iniciar sesión</button>
    </form>

    <span class="olvide">Olvidé mi contraseña</span>

</div>

<script>
function togglePwd(inputId, openId, closedId) {
    const input = document.getElementById(inputId);
    const open = document.getElementById(openId);
    const closed = document.getElementById(closedId);
    if (input.type === 'password') {
        input.type = 'text';
        open.style.display = 'none';
        closed.style.display = 'block';
    } else {
        input.type = 'password';
        open.style.display = 'block';
        closed.style.display = 'none';
    }
}
</script>
</body>
</html>