<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar perfil — LocalApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f0f2f0; display: flex; justify-content: center; font-family: "Instrument Sans", ui-sans-serif, system-ui, sans-serif; min-height: 100vh; padding: 1.5rem; }
        .app { width: 100%; max-width: 430px; background: white; border-radius: 2rem; box-shadow: 0 20px 60px rgba(0,0,0,0.08); padding: 2rem 1.75rem 2.5rem; }

        .page-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.75rem; }
        .btn-back { display: flex; align-items: center; text-decoration: none; color: #333; }
        .btn-back svg { width: 22px; height: 22px; }
        .page-title { font-size: 1.25rem; font-weight: 900; color: #111; }

        /* Banner aviso */
        .banner-info { display: flex; gap: 0.75rem; background: #eff6ff; border: 1.5px solid #93c5fd; border-radius: 0.85rem; padding: 0.85rem 1rem; margin-bottom: 1.5rem; font-size: 0.78rem; color: #1d4ed8; line-height: 1.5; }
        .banner-info svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 0.05rem; }

        /* Progress */
        .progress { display: flex; align-items: center; margin-bottom: 2rem; }
        .step-item { display: flex; flex-direction: column; align-items: center; gap: 0.3rem; flex: 1; position: relative; }
        .step-item:not(:last-child)::after { content: ''; position: absolute; top: 14px; left: 50%; width: 100%; height: 2px; background: #e5e7eb; z-index: 0; }
        .step-circle { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.72rem; font-weight: 800; position: relative; z-index: 1; }
        .step-circle.active { background: #a8df11; color: #1a1a1a; }
        .step-circle.inactive { background: #f0f0f0; color: #aaa; }
        .step-circle.done { background: #4a8a06; color: white; }
        .step-label { font-size: 0.65rem; font-weight: 700; }
        .step-label.active { color: #4a8a06; }
        .step-label.inactive { color: #aaa; }
        .step-label.done { color: #4a8a06; }

        /* Fields */
        .field { margin-bottom: 1.1rem; }
        .field label { display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 0.45rem; }
        .field input, .field select { width: 100%; border: 2px solid #e5e7eb; border-radius: 0.85rem; padding: 0.85rem 1rem; font-family: inherit; font-size: 0.92rem; color: #111; background: white; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        .field input:focus, .field select:focus { border-color: #a8df11; box-shadow: 0 0 0 4px rgba(168,223,17,0.12); }
        .field input[readonly] { background: #f9fafb; color: #6b7280; cursor: not-allowed; }
        .field-error { font-size: 0.75rem; color: #d41b11; margin-top: 0.3rem; font-weight: 600; }
        .field-hint { font-size: 0.72rem; color: #aaa; margin-top: 0.3rem; }

        /* Password toggle */
        .field-wrap { position: relative; }
        .field input.has-toggle { padding-right: 3rem; }
        .toggle-pwd { position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #aaa; display: flex; align-items: center; }
        .toggle-pwd svg { width: 18px; height: 18px; }

        /* Password requirements */
        .pwd-reqs { display: none; margin-top: 0.5rem; padding: 0.6rem 0.85rem; background: #f9f9f9; border-radius: 0.65rem; border: 1.5px solid #f0f0f0; }
        .pwd-req { font-size: 0.72rem; color: #d41b11; font-weight: 600; line-height: 1.8; }
        .pwd-req.ok { color: #4a8a06; }

        /* Upload zone */
        .doc-link { display: inline-flex; align-items: center; gap: 0.4rem; font-size: 0.75rem; color: #4a8a06; font-weight: 700; text-decoration: none; margin-bottom: 0.5rem; }
        .doc-link svg { width: 14px; height: 14px; }
        .upload-zone { display: flex; align-items: center; gap: 0.85rem; border: 2px dashed #e5e7eb; border-radius: 0.85rem; padding: 0.85rem 1rem; cursor: pointer; transition: border-color 0.2s; }
        .upload-zone:hover { border-color: #a8df11; }
        .upload-icon { width: 36px; height: 36px; background: #f0fde0; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .upload-icon svg { width: 18px; height: 18px; color: #4a8a06; }
        .upload-label { font-size: 0.82rem; font-weight: 700; color: #333; }
        .upload-hint { font-size: 0.7rem; color: #aaa; }
        .upload-name { font-size: 0.7rem; color: #4a8a06; margin-top: 0.15rem; }

        /* Nav buttons */
        .nav-btns { display: flex; gap: 0.75rem; margin-top: 1.5rem; }
        .btn-prev { flex: 1; background: white; color: #333; font-family: inherit; font-size: 0.9rem; font-weight: 700; padding: 0.85rem; border-radius: 999px; border: 2px solid #e5e7eb; cursor: pointer; transition: border-color 0.2s; }
        .btn-prev:hover { border-color: #a8df11; }
        .btn-next { flex: 2; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-family: inherit; font-size: 0.9rem; font-weight: 800; padding: 0.85rem; border-radius: 999px; border: none; cursor: pointer; box-shadow: 0 4px 14px rgba(168,223,17,0.3); transition: opacity 0.2s; }
        .btn-next:hover { opacity: 0.9; }
        .btn-submit { width: 100%; background: linear-gradient(135deg, #a8df11, #7cc10a); color: #1a1a1a; font-family: inherit; font-size: 1rem; font-weight: 800; padding: 0.9rem; border-radius: 999px; border: none; cursor: pointer; box-shadow: 0 6px 20px rgba(168,223,17,0.35); transition: opacity 0.2s, transform 0.15s; }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .section-title { font-size: 0.78rem; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #aaa; margin-bottom: 1rem; }
    </style>
</head>
<body>
<div class="app">

    <div class="page-header">
        <a href="{{ route('repartidor.perfil') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
            </svg>
        </a>
        <h1 class="page-title">Editar perfil</h1>
    </div>

    {{-- Banner informativo --}}
    <div class="banner-info">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z" />
        </svg>
        <span>Al guardar tus cambios, tu perfil quedará <strong>en revisión nuevamente</strong>. El equipo de LocalApp verificará tu información antes de reactivarte.</span>
    </div>

    {{-- Progress --}}
    <div class="progress">
        <div class="step-item">
            <div class="step-circle active" id="sc1">1</div>
            <span class="step-label active" id="sl1">Datos</span>
        </div>
        <div class="step-item">
            <div class="step-circle inactive" id="sc2">2</div>
            <span class="step-label inactive" id="sl2">Cuenta</span>
        </div>
        <div class="step-item">
            <div class="step-circle inactive" id="sc3">3</div>
            <span class="step-label inactive" id="sl3">Documentos</span>
        </div>
    </div>

    @if ($errors->any())
        <div style="background:#fff1f0;border:1px solid #fca5a5;color:#d41b11;font-size:0.78rem;font-weight:600;padding:0.75rem 1rem;border-radius:0.75rem;margin-bottom:1.25rem;">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('repartidor.actualizar-perfil') }}" method="POST" enctype="multipart/form-data" id="form-editar">
        @csrf

        {{-- ══ PASO 1: DATOS PERSONALES ══ --}}
        <div id="step1">
            <p class="section-title">Datos personales</p>

            <div class="field">
                <label>Nombre</label>
                <input type="text" name="per_nombre" value="{{ old('per_nombre', $persona?->per_nombre) }}" required>
                @error('per_nombre')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>Apellido paterno</label>
                <input type="text" name="per_paterno" value="{{ old('per_paterno', $persona?->per_paterno) }}" required>
                @error('per_paterno')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="field">
                <label>Teléfono</label>
                <input type="tel" name="per_telefono" value="{{ old('per_telefono', $persona?->per_telefono) }}" required placeholder="10 dígitos">
                @error('per_telefono')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="nav-btns">
                <button type="button" class="btn-next" style="flex:1" onclick="goTo(2)">Siguiente →</button>
            </div>
        </div>

        {{-- ══ PASO 2: CUENTA ══ --}}
        <div id="step2" style="display:none">
            <p class="section-title">Tu cuenta</p>

            <div class="field">
                <label>Correo electrónico</label>
                <input type="email" value="{{ $user->email }}" readonly>
                <p class="field-hint">El correo es tu identificador y no puede cambiarse aquí.</p>
            </div>

            <div class="field">
                <label>Nueva contraseña <span style="color:#aaa;font-weight:400;">(opcional)</span></label>
                <div class="field-wrap">
                    <input type="password" id="pwd" name="nueva_password"
                           placeholder="Dejar en blanco para no cambiarla" class="has-toggle"
                           autocomplete="new-password">
                    <button type="button" class="toggle-pwd" onclick="togglePwd('pwd','eye1o','eye1c')">
                        <svg id="eye1o" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <svg id="eye1c" style="display:none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0012 15a3 3 0 002.414-4.413M6.61 6.61A16.708 16.708 0 002.25 12s3.75 7.5 9.75 7.5a9.77 9.77 0 004.22-.958"/>
                        </svg>
                    </button>
                </div>
                <div class="pwd-reqs" id="pwd-reqs">
                    <div class="pwd-req" id="req-len"><span>✗</span> Mínimo 8 caracteres</div>
                    <div class="pwd-req" id="req-upper"><span>✗</span> Al menos una mayúscula</div>
                    <div class="pwd-req" id="req-num"><span>✗</span> Al menos un número</div>
                </div>
                @error('nueva_password')<p class="field-error">{{ $message }}</p>@enderror
            </div>

            <div class="field" id="confirm-field" style="display:none">
                <label>Confirmar nueva contraseña</label>
                <input type="password" name="nueva_password_confirmation"
                       placeholder="Repite la nueva contraseña" autocomplete="new-password">
            </div>

            <div class="nav-btns">
                <button type="button" class="btn-prev" onclick="goTo(1)">← Volver</button>
                <button type="button" class="btn-next" onclick="goTo(3)">Siguiente →</button>
            </div>
        </div>

        {{-- ══ PASO 3: DOCUMENTOS ══ --}}
        <div id="step3" style="display:none">
            <p class="section-title">Documentos <span style="font-weight:400;text-transform:none;letter-spacing:0;">— deja en blanco para mantener el actual</span></p>

            @php
                $docIne      = $docs->get(1);
                $docLicencia = $docs->get(2);
                $docCirc     = $docs->get(3);
                $docFoto     = $docs->get(4);
            @endphp

            {{-- INE --}}
            <div class="field">
                <label>INE / Identificación oficial</label>
                @if ($docIne)
                    <a href="{{ asset('storage/' . $docIne->dor_ruta) }}" target="_blank" class="doc-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        Ver INE actual
                    </a>
                @endif
                <label for="input-ine" class="upload-zone">
                    <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg></div>
                    <div>
                        <p class="upload-label">{{ $docIne ? 'Reemplazar INE' : 'Subir INE' }}</p>
                        <p class="upload-hint">Solo PDF — máx 4MB</p>
                        <p class="upload-name" id="ine-name"></p>
                    </div>
                </label>
                <input type="file" id="input-ine" name="ine" accept="application/pdf" style="display:none" onchange="showName(event,'ine-name')">
            </div>

            {{-- Licencia --}}
            <div class="field">
                <label>Licencia de conducir</label>
                @if ($docLicencia)
                    <a href="{{ asset('storage/' . $docLicencia->dor_ruta) }}" target="_blank" class="doc-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        Ver licencia actual
                    </a>
                @endif
                <label for="input-lic" class="upload-zone">
                    <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg></div>
                    <div>
                        <p class="upload-label">{{ $docLicencia ? 'Reemplazar licencia' : 'Subir licencia' }}</p>
                        <p class="upload-hint">Solo PDF — máx 4MB</p>
                        <p class="upload-name" id="lic-name"></p>
                    </div>
                </label>
                <input type="file" id="input-lic" name="licencia" accept="application/pdf" style="display:none" onchange="showName(event,'lic-name')">
            </div>

            {{-- Circulación --}}
            <div class="field">
                <label>Tarjeta de circulación</label>
                @if ($docCirc)
                    <a href="{{ asset('storage/' . $docCirc->dor_ruta) }}" target="_blank" class="doc-link">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                        Ver circulación actual
                    </a>
                @endif
                <label for="input-circ" class="upload-zone">
                    <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg></div>
                    <div>
                        <p class="upload-label">{{ $docCirc ? 'Reemplazar circulación' : 'Subir circulación' }}</p>
                        <p class="upload-hint">Solo PDF — máx 4MB</p>
                        <p class="upload-name" id="circ-name"></p>
                    </div>
                </label>
                <input type="file" id="input-circ" name="circulacion" accept="application/pdf" style="display:none" onchange="showName(event,'circ-name')">
            </div>

            {{-- Foto de perfil --}}
            <div class="field">
                <label>Foto de perfil</label>
                @if ($docFoto)
                    <img src="{{ asset('storage/' . $docFoto->dor_ruta) }}" id="preview-foto"
                         style="width:72px;height:72px;object-fit:cover;border-radius:50%;display:block;margin-bottom:0.5rem;">
                @else
                    <img id="preview-foto" style="display:none;width:72px;height:72px;object-fit:cover;border-radius:50%;margin-bottom:0.5rem;">
                @endif
                <label for="input-foto" class="upload-zone">
                    <div class="upload-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/></svg></div>
                    <div>
                        <p class="upload-label">{{ $docFoto ? 'Cambiar foto' : 'Subir foto' }}</p>
                        <p class="upload-hint">JPG, PNG, WEBP — máx 2MB</p>
                        <p class="upload-name" id="foto-name"></p>
                    </div>
                </label>
                <input type="file" id="input-foto" name="foto_perfil" accept="image/*" style="display:none" onchange="previewFoto(event)">
            </div>

            <div class="nav-btns">
                <button type="button" class="btn-prev" onclick="goTo(2)">← Volver</button>
                <button type="submit" class="btn-submit" style="flex:2;">Guardar cambios</button>
            </div>
        </div>

    </form>

</div>
<script>
function goTo(step) {
    [1,2,3].forEach(i => {
        document.getElementById('step'+i).style.display = 'none';
        const sc = document.getElementById('sc'+i), sl = document.getElementById('sl'+i);
        if (i < step)       { sc.className = 'step-circle done';    sl.className = 'step-label done'; }
        else if (i === step) { sc.className = 'step-circle active';  sl.className = 'step-label active'; }
        else                 { sc.className = 'step-circle inactive'; sl.className = 'step-label inactive'; }
    });
    document.getElementById('step'+step).style.display = 'block';
}

function togglePwd(inputId, openId, closedId) {
    const input = document.getElementById(inputId);
    document.getElementById(openId).style.display  = input.type === 'password' ? 'none'  : 'block';
    document.getElementById(closedId).style.display = input.type === 'password' ? 'block' : 'none';
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Indicador de requisitos de contraseña
const pwdInput = document.getElementById('pwd');
const reqs = document.getElementById('pwd-reqs');
const confirmField = document.getElementById('confirm-field');
pwdInput.addEventListener('focus', () => reqs.style.display = 'block');
pwdInput.addEventListener('input', () => {
    const v = pwdInput.value;
    [['len', v.length >= 8], ['upper', /[A-Z]/.test(v)], ['num', /[0-9]/.test(v)]].forEach(([key, ok]) => {
        const el = document.getElementById('req-'+key);
        el.classList.toggle('ok', ok);
        el.querySelector('span').textContent = ok ? '✓' : '✗';
    });
    confirmField.style.display = v.length > 0 ? 'block' : 'none';
});

function showName(event, id) {
    const file = event.target.files[0];
    if (file) document.getElementById(id).textContent = '📎 ' + file.name;
}

function previewFoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    document.getElementById('foto-name').textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
        const img = document.getElementById('preview-foto');
        img.src = e.target.result;
        img.style.display = 'block';
    };
    reader.readAsDataURL(file);
}

// Si hubo errores de validación, ir al paso correspondiente
@if ($errors->has('per_nombre') || $errors->has('per_paterno') || $errors->has('per_telefono'))
    goTo(1);
@elseif ($errors->has('nueva_password'))
    goTo(2);
@elseif ($errors->has('ine') || $errors->has('licencia') || $errors->has('circulacion') || $errors->has('foto_perfil'))
    goTo(3);
@endif
</script>
</body>
</html>
