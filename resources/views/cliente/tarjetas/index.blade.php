<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis tarjetas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/cliente/tarjetas.css')
</head>

<body>
    <div class="app">

        <div class="header">
            <a href="{{ route('cliente.perfil') }}" class="header-back">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </a>
            <h1>Mis tarjetas</h1>
            <div style="width:22px"></div>
        </div>

        <div class="body">

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('info'))
                <div class="alert alert-info">{{ session('info') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if($tarjetas->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                        </svg>
                    </div>
                    <p class="empty-title">Sin tarjetas guardadas</p>
                    <p class="empty-sub">Agrega una tarjeta para pagar más rápido</p>
                </div>
            @else
                <p class="seccion-titulo">Tarjetas guardadas</p>
                <div class="tarjeta-wrapper">
                    @foreach($tarjetas as $tarjeta)
                        <div class="tarjeta-full-card {{ $tarjeta->tar_es_default ? 'default' : '' }}">
                            <div style="display:flex;align-items:center;gap:1rem;">
                                <div class="tarjeta-icon">
                                    @if(strtolower($tarjeta->tar_brand) === 'visa')
                                        <svg viewBox="0 0 48 16" width="36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <text x="0" y="13" font-family="Arial,sans-serif" font-size="13" font-weight="900" fill="#1a1f71">VISA</text>
                                        </svg>
                                    @elseif(strtolower($tarjeta->tar_brand) === 'mastercard')
                                        <svg viewBox="0 0 36 24" width="34" xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="13" cy="12" r="10" fill="#eb001b"/>
                                            <circle cx="23" cy="12" r="10" fill="#f79e1b"/>
                                            <path d="M18 6.8a10 10 0 0 1 0 10.4A10 10 0 0 1 18 6.8z" fill="#ff5f00"/>
                                        </svg>
                                    @elseif(strtolower($tarjeta->tar_brand) === 'amex')
                                        <svg viewBox="0 0 48 16" width="36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <text x="0" y="13" font-family="Arial,sans-serif" font-size="9" font-weight="900" fill="#007bc1">AMEX</text>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="#888" style="width:22px;height:22px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="tarjeta-info">
                                    <p class="tarjeta-brand">{{ $tarjeta->brandLabel() }}</p>
                                    <p class="tarjeta-numero">•••• •••• •••• {{ $tarjeta->tar_last4 }}</p>
                                    <p class="tarjeta-exp">Vence {{ str_pad($tarjeta->tar_exp_month, 2, '0', STR_PAD_LEFT) }}/{{ $tarjeta->tar_exp_year }}</p>
                                </div>
                                @if($tarjeta->tar_es_default)
                                    <span class="tarjeta-default-badge">Principal</span>
                                @endif
                            </div>
                            <div class="tarjeta-actions">
                                @if(!$tarjeta->tar_es_default)
                                    <form method="POST" action="{{ route('cliente.tarjetas.predeterminar', $tarjeta->tar_id) }}" style="flex:1;">
                                        @csrf
                                        <button type="submit" class="btn-predeterminar" style="width:100%;">
                                            Usar como principal
                                        </button>
                                    </form>
                                @else
                                    <div style="flex:1;"></div>
                                @endif
                                <form method="POST" action="{{ route('cliente.tarjetas.eliminar', $tarjeta->tar_id) }}"
                                    onsubmit="return confirm('¿Eliminar esta tarjeta?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-eliminar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="2" stroke="currentColor" style="width:14px;height:14px;">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <a href="{{ route('cliente.tarjetas.agregar') }}" class="btn-agregar">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Agregar tarjeta
            </a>

        </div>

    </div>
</body>

</html>
