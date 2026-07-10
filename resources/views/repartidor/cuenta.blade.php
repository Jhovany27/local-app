<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Número de cuenta</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <style>
        .cuenta-body { flex:1; display:flex; flex-direction:column; padding:1.5rem 1.25rem 6rem; }
        .cuenta-card { background:#fff; border:1.5px solid #e8f5d0; border-radius:14px; padding:1.25rem; }
        .cuenta-desc { font-size:.82rem; color:#888; line-height:1.5; margin-bottom:1.25rem; }
        .cuenta-actual { background:#f8fdf0; border:1px solid #d4edaa; border-radius:10px; padding:.75rem 1rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center; }
        .cuenta-actual-label { font-size:.65rem; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#7ab80e; }
        .cuenta-actual-val   { font-size:.9rem; font-weight:800; color:#1a1a1a; }
        .cuenta-field { display:flex; flex-direction:column; gap:.4rem; }
        .cuenta-field label { font-size:.72rem; font-weight:700; color:#7ab80e; text-transform:uppercase; letter-spacing:.07em; }
        .cuenta-field input {
            width:100%; padding:.7rem .9rem; border:1.5px solid #d1d5db; border-radius:10px;
            font-size:1rem; font-family:'Instrument Sans',sans-serif; background:#f8fdf0;
            letter-spacing:.05em; box-sizing:border-box;
        }
        .cuenta-field input:focus { outline:none; border-color:#a8df11; }
        .cuenta-hint { font-size:.7rem; color:#aaa; margin-top:.2rem; }
        .cuenta-error { font-size:.75rem; color:#d41b11; margin-top:.2rem; }
        .btn-guardar-cuenta {
            width:100%; margin-top:1.25rem; padding:.85rem; background:linear-gradient(135deg,#a8df11,#7cc10a);
            border:none; border-radius:12px; font-family:'Instrument Sans',sans-serif;
            font-size:.95rem; font-weight:800; color:#1a1a1a; cursor:pointer;
            box-shadow:0 4px 14px rgba(168,223,17,.3);
        }
        .btn-guardar-cuenta:active { opacity:.85; }
        .alert-ok { background:#f0fde0; border:1.5px solid #c6f135; color:#3a6e04; border-radius:10px; padding:.7rem 1rem; font-size:.82rem; font-weight:700; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
        .alert-ok svg { width:16px; height:16px; flex-shrink:0; }
    </style>
</head>
<body>
<div class="app">

    <div class="header">
        <a href="{{ route('repartidor.perfil') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    <div class="cuenta-body">

        @if(session('cuenta_ok'))
            <div class="alert-ok">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ session('cuenta_ok') }}
            </div>
        @endif

        <div class="seccion">
            <p class="seccion-titulo">Número de cuenta bancaria</p>

            <div class="cuenta-card">
                <p class="cuenta-desc">
                    Este número se usará para realizarte los depósitos de tus liquidaciones periódicas.
                    Asegúrate de que sea una CLABE interbancaria de 18 dígitos válida.
                </p>

                @if ($repartidor->rep_numero_cuenta)
                    <div class="cuenta-actual">
                        <span class="cuenta-actual-label">Cuenta registrada</span>
                        <span class="cuenta-actual-val">{{ $repartidor->rep_numero_cuenta }}</span>
                    </div>
                @endif

                {{-- Estado Stripe Connect --}}
                @if ($repartidor->stripe_account_id)
                    <div style="background:#f0fde0;border:1.5px solid #a8df11;border-radius:10px;padding:.7rem 1rem;margin-bottom:1rem;display:flex;align-items:center;justify-content:space-between;">
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#4a8a06" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/></svg>
                            <span style="font-size:.82rem;font-weight:700;color:#4a8a06;">Cuenta Stripe conectada</span>
                        </div>
                        <a href="{{ route('repartidor.stripe.onboarding') }}" style="font-size:.72rem;color:#888;text-decoration:underline;">Actualizar</a>
                    </div>
                @else
                    <a href="{{ route('repartidor.stripe.onboarding') }}"
                       style="display:flex;align-items:center;justify-content:center;gap:.5rem;width:100%;padding:.75rem;background:#635bff;border-radius:10px;color:#fff;font-size:.88rem;font-weight:700;text-decoration:none;margin-bottom:1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="width:17px;height:17px;"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                        Conectar cuenta bancaria con Stripe
                    </a>
                    <p style="font-size:.72rem;color:#aaa;text-align:center;margin-bottom:1rem;">Necesario para recibir tus liquidaciones automáticamente.</p>
                @endif

                <form method="POST" action="{{ route('repartidor.cuenta.update') }}">
                    @csrf
                    <div class="cuenta-field">
                        <label>{{ $repartidor->rep_numero_cuenta ? 'Nueva cuenta' : 'Número de cuenta' }}</label>
                        <input
                            type="text"
                            name="rep_numero_cuenta"
                            value="{{ old('rep_numero_cuenta') }}"
                            placeholder="CLABE 18 dígitos"
                            maxlength="18"
                            inputmode="numeric"
                            autofocus>
                        <span class="cuenta-hint">CLABE interbancaria de 18 dígitos. Solo números, sin espacios.</span>
                        @error('rep_numero_cuenta')
                            <span class="cuenta-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="btn-guardar-cuenta">Guardar número de cuenta</button>
                </form>
            </div>
        </div>

    </div>

    <nav class="bottom-nav">
        <a href="{{ route('repartidor.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" /></svg>
        </a>
        <a href="{{ route('repartidor.historial') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" /></svg>
        </a>
        <a href="{{ route('repartidor.perfil') }}" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
        </a>
    </nav>

</div>
</body>
</html>
