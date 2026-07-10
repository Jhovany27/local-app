<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Pedidos disponibles</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/index.css')
</head>

<body>
    <div class="app">

        <div class="header">
            <div>
                <p class="header-title">
                    <span class="disponible-dot"></span>
                    Pedidos disponibles
                </p>
                <p class="header-sub">{{ $repartidor->user->persona?->per_nombre }} ·
                    {{ $repartidor->rep_tipo_vehiculo }}</p>
            </div>
            <form method="POST" action="{{ route('repartidor.logout') }}">
                @csrf
                <button type="submit"
                    style="background:none;border:none;cursor:pointer;color:#d41b11;font-size:0.75rem;font-weight:700;">
                    Salir
                </button>
            </form>
        </div>

        <div class="body">

            @if (session('success'))
                <div class="success-msg">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="error-msg">{{ session('error') }}</div>
            @endif

            {{-- BANNER DEUDA BLOQUEADO --}}
            @if ($deudaInfo['bloqueado'])
                <div class="deuda-banner deuda-banner-bloqueado">
                    <div class="deuda-banner-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <div class="deuda-banner-txt">
                        <p class="deuda-banner-titulo">Pedidos bloqueados</p>
                        <p class="deuda-banner-sub">Tu deuda acumulada <strong>${{ number_format($deudaInfo['total'], 2) }}</strong> supera el límite de <strong>${{ number_format($deudaInfo['limite'], 2) }}</strong>. Liquida al menos <strong>${{ number_format($deudaInfo['para_desbloqueo'], 2) }}</strong> para continuar.</p>
                    </div>
                </div>
            {{-- BANNER ALERTA 80% --}}
            @elseif ($deudaInfo['alerta'])
                <div class="deuda-banner deuda-banner-alerta">
                    <div class="deuda-banner-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="deuda-banner-txt">
                        <p class="deuda-banner-titulo">Límite de deuda próximo</p>
                        <p class="deuda-banner-sub">Llevas <strong>${{ number_format($deudaInfo['total'], 2) }}</strong> de deuda ({{ number_format($deudaInfo['porcentaje'], 0) }}% del límite). Liquida pronto para seguir operando sin interrupciones.</p>
                    </div>
                </div>
            @endif

            {{-- PENDIENTE DE LIQUIDACIÓN --}}
            @if (isset($pedidoPendienteLiq) && $pedidoPendienteLiq)
                <p class="seccion-titulo" style="color:#b45309;">Pendiente de liquidar</p>
                @php
                    $subtliq  = max(0, $pedidoPendienteLiq->ped_total - ($pedidoPendienteLiq->ped_costo_envio ?? 0));
                    $pctliq   = \App\Models\ConfiguracionComision::porcentajeActual();
                    $montoLiq = round($subtliq * (1 - $pctliq / 100), 2);
                @endphp
                <a href="{{ route('repartidor.liquidar', $pedidoPendienteLiq->ped_id) }}" class="activo-banner" style="background:linear-gradient(135deg,#fef3c7,#fde68a);border:1.5px solid #fbbf24;">
                    <div class="activo-texto">
                        <p style="color:#b45309;font-weight:800;">Entrega el efectivo a la tienda</p>
                        <p style="color:#92400e;">#{{ $pedidoPendienteLiq->ped_codigo }} · entregar ${{ number_format($montoLiq, 2) }}</p>
                    </div>
                    <div class="activo-arrow" style="background:rgba(0,0,0,.08);">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="#b45309">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            @endif

            {{-- PEDIDO ACTIVO --}}
            @if ($pedidoActivo)
                @php
                    $urlActivo = match ((int) $asignacionActiva->asr_estado) {
                        0 => route('repartidor.en-camino', $pedidoActivo->ped_id),
                        1 => route('repartidor.checklist', $pedidoActivo->ped_id),
                        2 => route('repartidor.entregar', $pedidoActivo->ped_id),
                        default => route('repartidor.index'),
                    };
                @endphp
                <p class="seccion-titulo">Tu pedido activo</p>
                <a href="{{ $urlActivo }}" class="activo-banner">
                    <div class="activo-texto">
                        <p>
                            {{ match ((int) $asignacionActiva->asr_estado) {
                                0 => 'Yendo a la tienda',
                                1 => 'Recogiendo pedido',
                                2 => 'En camino al cliente',
                                default => 'En curso',
                            } }}
                        </p>
                        <p>{{ $pedidoActivo->tienda->tie_nombre }}</p>
                    </div>
                    <div class="activo-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            @endif

            {{-- DISPONIBLES --}}
            <p class="seccion-titulo">Disponibles ({{ $pedidos->count() }})</p>

            @forelse($pedidos as $pedido)
                <a href="{{ $deudaInfo['bloqueado'] ? '#' : route('repartidor.pedido', $pedido->ped_id) }}"
                   class="pedido-card {{ $deudaInfo['bloqueado'] ? 'pedido-card-bloqueado' : '' }}"
                   {{ $deudaInfo['bloqueado'] ? 'onclick=return false' : '' }}>
                    <div class="pedido-header">
                        <div>
                            <p class="pedido-tienda">{{ $pedido->tienda->tie_nombre }}</p>
                            <p class="pedido-hora">{{ $pedido->ped_fecha_pedido->diffForHumans() }}</p>
                        </div>
                        <p class="pedido-total">${{ number_format($pedido->ped_total, 2) }}</p>
                    </div>
                    <div class="pedido-body">
                        <p class="pedido-items">
                            <span>{{ $pedido->detalles->count() }}</span> producto(s) ·
                            {{ $pedido->cliente?->user?->persona?->per_nombre }}
                            {{ $pedido->cliente?->user?->persona?->per_paterno }}
                        </p>
                        <span
                            style="font-size:0.68rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:999px;
        {{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta'
            ? 'background:#eff6ff;border:1px solid #93c5fd;color:#1d4ed8;'
            : 'background:#f0fde0;border:1px solid #c6f135;color:#4a8a06;' }}">
                            {{ strtolower($pedido->pago?->pag_metodo_pago) === 'tarjeta' ? 'Tarjeta' : 'Efectivo' }}
                        </span>

                        <div class="pedido-arrow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </div>
                </a>

            @empty
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                        </svg>
                    </div>
                    <p>Sin pedidos disponibles</p>
                    <span>Los pedidos listos para domicilio aparecerán aquí</span>
                </div>
            @endforelse

        </div>

        <nav class="bottom-nav">
            <a href="{{ route('repartidor.index') }}" class="nav-item active">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
                </svg>
            </a>
            <a href="{{ route('repartidor.historial') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
                </svg>
            </a>
            <a href="{{ route('repartidor.perfil') }}" class="nav-item">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
            </a>
        </nav>

    </div>
</body>

</html>
