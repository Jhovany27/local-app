<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear cuenta — LocalApp</title>
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
            margin-bottom: 1.75rem;
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
            margin-bottom: 1.75rem;
        }

        .titulo-wrap h1 {
            font-size: 1.6rem;
            font-weight: 900;
            color: #111;
            margin-bottom: 0.3rem;
        }

        .titulo-wrap p { font-size: 0.82rem; color: #999; }

        /* ── CAMPOS ── */
        .field { margin-bottom: 1.1rem; }

        .field label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0.45rem;
        }

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

        .field-wrap { position: relative; }
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

        .field-error {
            font-size: 0.75rem;
            color: #d41b11;
            margin-top: 0.3rem;
            font-weight: 600;
        }

        /* ── CHECKBOX ── */
        .check-wrap {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            margin: 1.25rem 0;
            padding: 1rem;
            background: #f8fdf0;
            border: 1.5px solid #e8f5d0;
            border-radius: 0.85rem;
        }

        .check-wrap input[type="checkbox"] {
            width: 18px; height: 18px;
            accent-color: #a8df11;
            cursor: pointer;
            flex-shrink: 0;
            margin-top: 0.1rem;
        }

        .check-wrap label {
            font-size: 0.82rem;
            color: #555;
            line-height: 1.5;
            cursor: pointer;
        }

        .check-wrap a {
            color: #111;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
        }

        /* ── DIVIDER ── */
        .divider {
            height: 1px;
            background: #f0f0f0;
            margin: 0.5rem 0 1.25rem;
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
        }

        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }

        /* ── YA TIENES CUENTA ── */
        .ya-cuenta {
            text-align: center;
            font-size: 0.82rem;
            color: #999;
            margin-top: 1.25rem;
        }

        .ya-cuenta a {
            color: #111;
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: 3px;
        }
    </style>
</head>
<body>
<div class="app">

    {{-- HEADER --}}
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

    {{-- TÍTULO --}}
    <div class="titulo-wrap">
        <h1>Crea tu cuenta</h1>
        <p>Completa tus datos para continuar</p>
    </div>

    {{-- FORM --}}
    <form method="POST" action="{{ route('cliente.registro.store') }}">
        @csrf

        <div class="field">
            <label>Nombre</label>
            <input type="text" name="per_nombre" value="{{ old('per_nombre') }}"
                   placeholder="Tu nombre" required>
            @error('per_nombre')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Apellido paterno</label>
            <input type="text" name="per_paterno" value="{{ old('per_paterno') }}"
                   placeholder="Apellido paterno" required>
            @error('per_paterno')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Apellido materno</label>
            <input type="text" name="per_materno" value="{{ old('per_materno') }}"
                   placeholder="Apellido materno">
            @error('per_materno')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Teléfono</label>
            <input type="tel" name="per_telefono" value="{{ old('per_telefono') }}"
                   placeholder="10 dígitos" required>
            @error('per_telefono')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Correo electrónico</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   placeholder="tu@correo.com" required autocomplete="email">
            @error('email')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Contraseña</label>
            <div class="field-wrap">
                <input type="password" id="reg-pwd" name="password" class="has-toggle"
                       placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                <button type="button" class="toggle-pwd" onclick="togglePwd('reg-pwd','eyeR1','eyeR1c')">
                    <svg id="eyeR1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg id="eyeR1c" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M9.88 5.458A9.77 9.77 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a16.708 16.708 0 01-4.293 5.774M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                    </svg>
                </button>
            </div>
            <div id="pwd-reqs" style="display:none;margin-top:0.5rem;padding:0.6rem 0.85rem;background:#f9f9f9;border-radius:0.65rem;border:1.5px solid #f0f0f0;">
                <div class="pwd-req" id="req-len" style="font-size:0.72rem;color:#d41b11;font-weight:600;line-height:1.8;transition:color 0.15s;"><span>✗</span> Mínimo 8 caracteres</div>
                <div class="pwd-req" id="req-upper" style="font-size:0.72rem;color:#d41b11;font-weight:600;line-height:1.8;transition:color 0.15s;"><span>✗</span> Al menos una mayúscula</div>
                <div class="pwd-req" id="req-num" style="font-size:0.72rem;color:#d41b11;font-weight:600;line-height:1.8;transition:color 0.15s;"><span>✗</span> Al menos un número</div>
            </div>
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Confirmar contraseña</label>
            <div class="field-wrap">
                <input type="password" id="reg-pwd-conf" name="password_confirmation" class="has-toggle"
                       placeholder="Repite tu contraseña" required>
                <button type="button" class="toggle-pwd" onclick="togglePwd('reg-pwd-conf','eyeR2','eyeR2c')">
                    <svg id="eyeR2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg id="eyeR2c" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M9.88 5.458A9.77 9.77 0 0112 4.5c6 0 9.75 7.5 9.75 7.5a16.708 16.708 0 01-4.293 5.774M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- AVISO DE PRIVACIDAD --}}
        <div class="check-wrap">
            <input type="checkbox" id="privacidad" name="privacidad" required>
            <label for="privacidad">
                Acepto el <a href="#">aviso de privacidad</a> y los
                <a href="#">términos de uso</a>
            </label>
        </div>

        <div class="divider"></div>

        <button type="submit" class="btn-submit">Continuar</button>

    </form>

    <p class="ya-cuenta">
        ¿Ya tienes cuenta? <a href="{{ route('cliente.login') }}">Inicia sesión</a>
    </p>

</div>
<script>
function togglePwd(inputId, openId, closedId) {
    const input = document.getElementById(inputId);
    const open  = document.getElementById(openId);
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
const regPwd = document.getElementById('reg-pwd');
const reqs   = document.getElementById('pwd-reqs');
regPwd.addEventListener('focus', () => reqs.style.display = 'block');
regPwd.addEventListener('input', () => {
    const v = regPwd.value;
    [['len', v.length >= 8], ['upper', /[A-Z]/.test(v)], ['num', /[0-9]/.test(v)]].forEach(([key, ok]) => {
        const el = document.getElementById('req-' + key);
        el.style.color = ok ? '#4a8a06' : '#d41b11';
        el.querySelector('span').textContent = ok ? '✓' : '✗';
    });
});
</script>
</body>
</html>