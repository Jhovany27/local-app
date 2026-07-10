<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pagar a la tienda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <style>
        .liq-body { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 2rem 1.5rem; gap: 1.75rem; }

        .liq-icon { width: 64px; height: 64px; background: #fef3c7; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
        .liq-icon svg { width: 32px; height: 32px; color: #d97706; }

        .liq-titulo    { font-size: 1.1rem; font-weight: 900; color: #1a1a1a; text-align: center; }
        .liq-subtitulo { font-size: .82rem; color: #888; text-align: center; line-height: 1.5; }

        .liq-info { background: #fef3c7; border: 1.5px solid #fcd34d; border-radius: 12px; padding: .85rem 1.25rem; width: 100%; display: flex; justify-content: space-between; align-items: center; }
        .liq-info-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #92400e; }
        .liq-info-val   { font-size: .88rem; font-weight: 700; color: #1a1a1a; margin-top: .1rem; }
        .liq-info-monto { font-size: 1.25rem; font-weight: 900; color: #78350f; }

        .liq-pin-label { font-size: .68rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #555; }

        .liq-pin-inputs { display: flex; gap: .75rem; justify-content: center; }
        .liq-pin-inputs input {
            width: 3rem; height: 3.75rem; text-align: center;
            font-size: 1.5rem; font-weight: 900; color: #1a1a1a;
            border: 2px solid #d1d5db; border-radius: 12px;
            background: #fef9ee; outline: none;
            font-family: inherit;
            transition: border-color .15s;
        }
        .liq-pin-inputs input:focus { border-color: #a8df11; }
        .liq-pin-inputs input.pin-error { border-color: #fca5a5; background: #fff1f0; }

        .liq-error { display: flex; align-items: center; gap: .5rem; font-size: .78rem; font-weight: 600; color: #d41b11; background: #fff1f0; border: 1px solid #fca5a5; border-radius: 8px; padding: .55rem .85rem; width: 100%; }
        .liq-error svg { width: 15px; height: 15px; flex-shrink: 0; }

        .liq-btn {
            width: 100%; padding: .85rem; background: linear-gradient(135deg,#a8df11,#7cc10a);
            border: none; border-radius: 12px; font-family: inherit;
            font-size: 1rem; font-weight: 800; color: #1a1a1a; cursor: pointer;
            box-shadow: 0 4px 16px rgba(168,223,17,.3);
        }
        .liq-btn:active { opacity: .85; }
        .liq-btn:disabled { opacity: .5; cursor: not-allowed; }

        .liq-bloqueado { background: #fff1f0; border: 1.5px solid #fca5a5; border-radius: 14px; padding: 1.5rem; text-align: center; width: 100%; }
        .liq-bloqueado svg { width: 40px; height: 40px; color: #fca5a5; margin-bottom: .75rem; }
        .liq-bloqueado-title { font-size: .95rem; font-weight: 800; color: #d41b11; margin-bottom: .4rem; }
        .liq-bloqueado-txt   { font-size: .8rem; color: #888; line-height: 1.4; }

        .pasos { display: flex; flex-direction: column; gap: .6rem; width: 100%; }
        .paso  { display: flex; align-items: flex-start; gap: .75rem; }
        .paso-num { width: 22px; height: 22px; border-radius: 50%; background: #fcd34d; color: #78350f; font-size: .72rem; font-weight: 900; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: .05rem; }
        .paso-txt  { font-size: .8rem; color: #555; line-height: 1.4; }
        .paso-txt strong { color: #1a1a1a; }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <div style="width:22px"></div>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    <div class="liq-body">

        <div class="liq-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
            </svg>
        </div>

        <div>
            <p class="liq-titulo">Pagar a la tienda</p>
            <p class="liq-subtitulo" style="margin-top:.4rem;">
                Entrega el efectivo a <strong>{{ $pedido->tienda->tie_nombre }}</strong><br>y pídeles el código de confirmación.
            </p>
        </div>

        {{-- MONTO A PAGAR --}}
        <div class="liq-info">
            <div>
                <p class="liq-info-label">Pedido</p>
                <p class="liq-info-val">#{{ $pedido->ped_codigo }}</p>
            </div>
            <div style="text-align:right;">
                <p class="liq-info-label">Pagas a la tienda</p>
                <p class="liq-info-monto">${{ number_format($montoParaTienda, 2) }}</p>
                <p style="font-size:.65rem;color:#92400e;margin-top:.1rem;">
                    ${{ number_format($pedido->ped_total - ($pedido->ped_costo_envio ?? 0), 2) }} − {{ $pctComision }}% comisión
                </p>
            </div>
        </div>

        {{-- PASOS --}}
        <div class="pasos">
            <div class="paso">
                <div class="paso-num">1</div>
                <p class="paso-txt">Entrega <strong>${{ number_format($montoParaTienda, 2) }}</strong> en efectivo a la tienda.</p>
            </div>
            <div class="paso">
                <div class="paso-num">2</div>
                <p class="paso-txt">Pide el código de 4 dígitos a la tienda.</p>
            </div>
            <div class="paso">
                <div class="paso-num">3</div>
                <p class="paso-txt">Ingresa el código y recoge el pedido.</p>
            </div>
        </div>

        @if ($bloqueado)
            <div class="liq-bloqueado">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                <p class="liq-bloqueado-title">Demasiados intentos fallidos</p>
                <p class="liq-bloqueado-txt">Has agotado los 5 intentos. Contacta con la tienda o soporte para resolver esta situación.</p>
            </div>
        @else
            @if ($errors->has('pin'))
                <div class="liq-error">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" /></svg>
                    {{ $errors->first('pin') }}
                </div>
            @endif

            <div style="width:100%;display:flex;flex-direction:column;gap:.75rem;align-items:center;">
                <p class="liq-pin-label">Código de la tienda (4 dígitos)</p>
                <form method="POST" action="{{ route('repartidor.validar-pin-tienda', $pedido->ped_id) }}" id="pin-form" style="width:100%;display:flex;flex-direction:column;gap:1.25rem;align-items:center;">
                    @csrf
                    <input type="hidden" name="pin" id="pin-hidden">
                    <div class="liq-pin-inputs">
                        <input type="number" inputmode="numeric" maxlength="1" id="p0" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9" autofocus>
                        <input type="number" inputmode="numeric" maxlength="1" id="p1" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                        <input type="number" inputmode="numeric" maxlength="1" id="p2" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                        <input type="number" inputmode="numeric" maxlength="1" id="p3" class="{{ $errors->has('pin') ? 'pin-error' : '' }}" min="0" max="9">
                    </div>
                    <button type="submit" class="liq-btn" id="btn-submit" disabled>Confirmar y recoger pedido</button>
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
        const pin = inputs.map(i => i.value.replace(/\D/g, '').slice(0,1)).join('');
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
