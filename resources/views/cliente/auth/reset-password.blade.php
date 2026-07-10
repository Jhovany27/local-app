<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva contraseña — LocalApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: white; display: flex; justify-content: center; font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; min-height: 100vh; }
        .app { width: 100%; max-width: 430px; min-height: 100vh; background: white; display: flex; flex-direction: column; padding: 0 1.5rem 2rem; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 0 1rem; border-bottom: 1px solid #f0f0f0; margin-bottom: 2rem; }
        .btn-back { display: flex; align-items: center; text-decoration: none; color: #333; }
        .btn-back svg { width: 22px; height: 22px; }
        .header-logo img { height: 36px; }
        .titulo-wrap { margin-bottom: 2rem; }
        .titulo-wrap h1 { font-size: 1.6rem; font-weight: 900; color: #111; margin-bottom: 0.4rem; }
        .titulo-wrap p { font-size: 0.85rem; color: #999; }
        .field { margin-bottom: 1.25rem; }
        .field label { display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.5rem; }
        .field-wrap { position: relative; }
        .field input { width: 100%; border: 2px solid #e5e7eb; border-radius: 0.85rem; padding: 0.85rem 1rem; font-family: inherit; font-size: 0.92rem; color: #111; background: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        .field input.has-toggle { padding-right: 3rem; }
        .field input:focus { border-color: #a8df11; box-shadow: 0 0 0 4px rgba(168,223,17,0.12); }
        .field-error { font-size: 0.75rem; color: #d41b11; margin-top: 0.3rem; font-weight: 600; }
        .toggle-pwd { position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #aaa; display: flex; align-items: center; transition: color 0.15s; }
        .toggle-pwd:hover { color: #4a8a06; }
        .toggle-pwd svg { width: 18px; height: 18px; }
        .pwd-reqs { display: none; margin-top: 0.5rem; padding: 0.6rem 0.85rem; background: #f9f9f9; border-radius: 0.65rem; border: 1.5px solid #f0f0f0; }
        .pwd-req { font-size: 0.72rem; color: #d41b11; font-weight: 600; line-height: 1.8; transition: color 0.15s; }
        .pwd-req.ok { color: #4a8a06; }
        .btn-submit { width: 100%; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-family: inherit; font-size: 1rem; font-weight: 800; padding: 0.9rem; border-radius: 999px; border: none; cursor: pointer; box-shadow: 0 6px 20px rgba(168,223,17,0.35); transition: opacity 0.2s, transform 0.15s; }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .error-msg { background: #fff1f0; border: 1px solid #fca5a5; color: #d41b11; font-size: 0.78rem; font-weight: 600; padding: 0.6rem 0.9rem; border-radius: 0.75rem; margin-bottom: 1rem; }
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
        <h1>Nueva contraseña</h1>
        <p>Elige una contraseña segura para tu cuenta.</p>
    </div>

    @error('email')
        <div class="error-msg">{{ $message }}</div>
    @enderror

    <form method="POST" action="{{ route('cliente.password.update') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="field">
            <label>Nueva contraseña</label>
            <div class="field-wrap">
                <input type="password" id="pwd" name="password"
                       placeholder="Mínimo 8 caracteres" required class="has-toggle"
                       autocomplete="new-password">
                <button type="button" class="toggle-pwd" onclick="togglePwd('pwd','eye1o','eye1c')">
                    <svg id="eye1o" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg id="eye1c" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                    </svg>
                </button>
            </div>
            <div class="pwd-reqs" id="pwd-reqs">
                <div class="pwd-req" id="req-len"><span>✗</span> Mínimo 8 caracteres</div>
                <div class="pwd-req" id="req-upper"><span>✗</span> Al menos una mayúscula</div>
                <div class="pwd-req" id="req-num"><span>✗</span> Al menos un número</div>
            </div>
            @error('password')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="field">
            <label>Confirmar contraseña</label>
            <div class="field-wrap">
                <input type="password" id="pwd2" name="password_confirmation"
                       placeholder="Repite tu nueva contraseña" required class="has-toggle"
                       autocomplete="new-password">
                <button type="button" class="toggle-pwd" onclick="togglePwd('pwd2','eye2o','eye2c')">
                    <svg id="eye2o" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <svg id="eye2c" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                    </svg>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-submit" style="margin-top:0.5rem;">Guardar contraseña</button>
    </form>

</div>
<script>
function togglePwd(inputId, openId, closedId) {
    const input = document.getElementById(inputId);
    document.getElementById(openId).style.display  = input.type === 'password' ? 'none'  : 'block';
    document.getElementById(closedId).style.display = input.type === 'password' ? 'block' : 'none';
    input.type = input.type === 'password' ? 'text' : 'password';
}
const pwdInput = document.getElementById('pwd');
const reqs     = document.getElementById('pwd-reqs');
pwdInput.addEventListener('focus', () => reqs.style.display = 'block');
pwdInput.addEventListener('input', () => {
    const v = pwdInput.value;
    [['len', v.length >= 8], ['upper', /[A-Z]/.test(v)], ['num', /[0-9]/.test(v)]].forEach(([key, ok]) => {
        const el = document.getElementById('req-' + key);
        el.classList.toggle('ok', ok);
        el.querySelector('span').textContent = ok ? '✓' : '✗';
    });
});
</script>
</body>
</html>
