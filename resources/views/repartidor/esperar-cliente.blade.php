<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Esperando al cliente</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <style>
        .esp-body { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1.5rem; gap: 1.5rem; }

        .esp-icon { width: 64px; height: 64px; background: #eff6ff; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .esp-icon svg { width: 32px; height: 32px; color: #3b82f6; }

        .esp-titulo    { font-size: 1.1rem; font-weight: 900; color: #1a1a1a; text-align: center; }
        .esp-subtitulo { font-size: .82rem; color: #888; text-align: center; line-height: 1.5; }

        .cliente-info { background: #f8fdf0; border: 1.5px solid #d4edaa; border-radius: 12px; padding: .85rem 1.25rem; width: 100%; display: flex; flex-direction: column; gap: .6rem; }
        .ci-row { display: flex; align-items: center; gap: .65rem; }
        .ci-icon { width: 30px; height: 30px; background: #f0fde0; border-radius: .5rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .ci-icon svg { width: 14px; height: 14px; color: #4a8a06; }
        .ci-label { font-size: .62rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #aaa; }
        .ci-val   { font-size: .85rem; font-weight: 700; color: #1a1a1a; }

        .pin-label { font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #555; }

        .pin-inputs { display: flex; gap: .75rem; justify-content: center; }
        .pin-inputs input {
            width: 3rem; height: 3.75rem; text-align: center;
            font-size: 1.5rem; font-weight: 900; color: #1a1a1a;
            border: 2px solid #d1d5db; border-radius: 12px;
            background: #f8fdf0; outline: none;
            font-family: inherit;
            transition: border-color .15s;
        }
        .pin-inputs input:focus { border-color: #a8df11; }
        .pin-inputs input.pin-error { border-color: #fca5a5; background: #fff1f0; }

        .pin-error-msg { display: flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 600; color: #d41b11; background: #fff1f0; border: 1px solid #fca5a5; border-radius: 8px; padding: .55rem .85rem; width: 100%; }
        .pin-error-msg svg { width: 15px; height: 15px; flex-shrink: 0; }

        .btn-confirmar {
            width: 100%; padding: .9rem; background: linear-gradient(135deg,#a8df11,#7cc10a);
            border: none; border-radius: 12px; font-family: inherit;
            font-size: 1rem; font-weight: 800; color: #1a1a1a; cursor: pointer;
            box-shadow: 0 4px 16px rgba(168,223,17,.3);
        }
        .btn-confirmar:disabled { opacity: .5; cursor: not-allowed; }
        .btn-confirmar:active:not(:disabled) { opacity: .85; }

        .bloqueado-box { background: #fff1f0; border: 1.5px solid #fca5a5; border-radius: 14px; padding: 1.5rem; text-align: center; width: 100%; }
        .bloqueado-box svg { width: 40px; height: 40px; color: #fca5a5; margin-bottom: .75rem; }
        .bloqueado-title { font-size: .95rem; font-weight: 800; color: #d41b11; margin-bottom: .4rem; }
        .bloqueado-txt   { font-size: .8rem; color: #888; line-height: 1.4; }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <a href="{{ route('repartidor.entregar', $pedido->ped_id) }}" class="header-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    <div class="esp-body">

        <div class="esp-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </div>

        <div>
            <p class="esp-titulo">Esperando al cliente</p>
            <p class="esp-subtitulo" style="margin-top:.4rem;">
                Pide al cliente que genere su código en la app.<br>Ingresa el código de 4 dígitos para confirmar la entrega.
            </p>
        </div>

        {{-- DATOS DEL CLIENTE --}}
        <div class="cliente-info">
            <div class="ci-row">
                <div class="ci-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                    </svg>
                </div>
                <div>
                    <p class="ci-label">Cliente</p>
                    <p class="ci-val">{{ $persona?->per_nombre }} {{ $persona?->per_paterno }}</p>
                </div>
            </div>
            <div class="ci-row">
                <div class="ci-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                    </svg>
                </div>
                <div>
                    <p class="ci-label">Teléfono</p>
                    <p class="ci-val">{{ $persona?->per_telefono ?? '—' }}</p>
                </div>
            </div>
            <div class="ci-row">
                <div class="ci-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                    </svg>
                </div>
                <div>
                    <p class="ci-label">Dirección</p>
                    <p class="ci-val">{{ $direccion?->drc_calle }}, {{ $direccion?->drc_ciudad }}</p>
                    @if($direccion?->drc_referencias)
                        <p style="font-size:.72rem;color:#888;margin-top:.1rem;">Ref: {{ $direccion->drc_referencias }}</p>
                    @endif
                </div>
            </div>
        </div>

        @if($bloqueado)
            <div class="bloqueado-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <p class="bloqueado-title">Demasiados intentos fallidos</p>
                <p class="bloqueado-txt">Has agotado los 5 intentos. Contacta con soporte para resolver esta situación.</p>
            </div>
        @else
            @if($errors->has('pin'))
                <div class="pin-error-msg">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                    {{ $errors->first('pin') }}
                </div>
            @endif

            <div style="width:100%;display:flex;flex-direction:column;gap:.75rem;align-items:center;">
                <p class="pin-label">Código del cliente (4 dígitos)</p>
                <form method="POST" action="{{ route('repartidor.validar-pin-entrega', $pedido->ped_id) }}" id="pin-form" style="width:100%;display:flex;flex-direction:column;gap:1.25rem;align-items:center;">
                    @csrf
                    <input type="hidden" name="pin" id="pin-hidden">
                    <div class="pin-inputs">
                        <input type="number" inputmode="numeric" maxlength="1" id="p0" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9" autofocus>
                        <input type="number" inputmode="numeric" maxlength="1" id="p1" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                        <input type="number" inputmode="numeric" maxlength="1" id="p2" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                        <input type="number" inputmode="numeric" maxlength="1" id="p3" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                    </div>
                    <button type="submit" class="btn-confirmar" id="btn-submit" disabled>Confirmar entrega</button>
                </form>
            </div>
        @endif

    </div>

</div>

<script>
    const inputs = [document.getElementById('p0'), document.getElementById('p1'), document.getElementById('p2'), document.getElementById('p3')];
    const btn    = document.getElementById('btn-submit');
    const hidden = document.getElementById('pin-hidden');

    function updatePin() {
        const pin = inputs.map(i => i.value.replace(/\D/g, '').slice(0, 1)).join('');
        hidden.value = pin;
        if (btn) btn.disabled = pin.length < 4;
    }

    inputs.forEach((inp, idx) => {
        if (!inp) return;
        inp.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(-1);
            updatePin();
            if (this.value && idx < 3) inputs[idx + 1].focus();
        });
        inp.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) {
                inputs[idx - 1].focus();
                inputs[idx - 1].value = '';
                updatePin();
            }
        });
    });
</script>
</body>
</html>
