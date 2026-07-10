<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis ganancias</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/repartidor/perfil.css')
    <style>
        .saldo-strip {
            background: #f5fde8;
            border-bottom: 1.5px solid #e0f5b0;
            padding: 1.25rem 1.25rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: .75rem;
        }
        .saldo-item { display: flex; flex-direction: column; gap: .15rem; }
        .saldo-label { font-size: .6rem; font-weight: 800; text-transform: uppercase; letter-spacing: .1em; color: #7ab80e; }
        .saldo-val   { font-size: 1.3rem; font-weight: 900; color: #1a1a1a; }
        .saldo-hint  { font-size: .65rem; color: #aaa; }
        .saldo-divider { width: 1.5px; background: #d4eda0; border-radius: 2px; }

        .alert-deuda {
            margin: 0 1.25rem;
            background: #fff7ed;
            border: 1.5px solid #fed7aa;
            border-radius: 12px;
            padding: .8rem 1rem;
            display: flex;
            gap: .65rem;
            align-items: flex-start;
        }
        .alert-deuda svg { width: 17px; height: 17px; color: #ea580c; flex-shrink: 0; margin-top: .05rem; }
        .alert-deuda-txt { font-size: .78rem; font-weight: 600; color: #c2410c; line-height: 1.4; }

        .deuda-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .75rem 1rem;
            border-bottom: 1px solid #f5f5f5;
        }
        .deuda-row:last-child { border-bottom: none; }
        .deuda-codigo { font-size: .82rem; font-weight: 700; color: #c2410c; }
        .deuda-fecha  { font-size: .68rem; color: #aaa; margin-top: .1rem; }
        .deuda-monto  { font-size: .88rem; font-weight: 800; color: #c2410c; }

        .mov-row {
            display: flex;
            align-items: center;
            gap: .85rem;
            padding: .8rem 1rem;
            border-bottom: 1px solid #f5f5f5;
        }
        .mov-row:last-child { border-bottom: none; }
        .mov-icon { width: 34px; height: 34px; border-radius: .65rem; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .mov-icon svg { width: 15px; height: 15px; }
        .mov-icon-abono  { background: #f0fde0; color: #4a8a06; }
        .mov-icon-otro   { background: #fff1f0; color: #d41b11; }
        .mov-desc  { flex: 1; font-size: .8rem; font-weight: 600; color: #1a1a1a; line-height: 1.3; }
        .mov-fecha { font-size: .65rem; color: #aaa; margin-top: .1rem; }
        .mov-monto-pos      { font-size: .88rem; font-weight: 800; color: #4a8a06; }
        .mov-monto-neg      { font-size: .88rem; font-weight: 800; color: #d41b11; }
        .mov-monto-pendiente{ font-size: .78rem; font-weight: 700; color: #b45309; background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: .15rem .5rem; white-space: nowrap; }
        .mov-icon-tarjeta   { background: #eff6ff; color: #2563eb; }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .65rem;
            padding: 2.5rem 1rem;
            color: #ccc;
            font-size: .82rem;
            text-align: center;
        }
        .empty-state svg { width: 38px; height: 38px; }
    </style>
</head>
<body>
<div class="app">

    {{-- HEADER --}}
    <div class="header">
        <a href="{{ route('repartidor.perfil') }}" class="btn-back">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </a>
        <div class="header-logo"><img src="{{ asset('images/Logo_local_app.png') }}" alt="LocalApp"></div>
        <div style="width:22px"></div>
    </div>

    {{-- SALDO STRIP --}}
    @if ($wallet)
        <div class="saldo-strip">
            <div class="saldo-item">
                <span class="saldo-label">Disponible</span>
                <span class="saldo-val">${{ number_format($wallet->wal_saldo_disponible, 2) }}</span>
                <span class="saldo-hint">Ya en tu bolsillo</span>
            </div>
            <div class="saldo-item" style="padding-left:.75rem; border-left: 1.5px solid #d4eda0;">
                <span class="saldo-label">Pendiente</span>
                <span class="saldo-val">${{ number_format($wallet->wal_saldo_pendiente, 2) }}</span>
                <span class="saldo-hint">Por liquidar</span>
            </div>
        </div>
    @endif

    <div class="body">

        {{-- ALERTA DEUDAS --}}
        @if ($deudas->count() > 0)
            <div class="seccion">
                <div class="alert-deuda">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                    <span class="alert-deuda-txt">
                        Debes <strong>${{ number_format($deudas->sum('dre_monto'), 2) }}</strong> a la plataforma por comisiones de pedidos en efectivo.
                    </span>
                </div>
            </div>

            <div class="seccion">
                <p class="seccion-titulo">Deudas con la plataforma</p>
                <div class="info-card">
                    @foreach ($deudas as $deuda)
                        <div class="deuda-row">
                            <div>
                                <p class="deuda-codigo">Pedido #{{ $deuda->pedido?->ped_codigo ?? $deuda->dre_fk_pedido }}</p>
                                <p class="deuda-fecha">{{ $deuda->dre_fecha->format('d/m/Y') }}</p>
                            </div>
                            <span class="deuda-monto">-${{ number_format($deuda->dre_monto, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- HISTORIAL --}}
        <div class="seccion">
            <p class="seccion-titulo">Historial de movimientos</p>

            @if ($wallet && $wallet->movimientos->count() > 0)
                <div class="info-card">
                    @foreach ($wallet->movimientos as $mov)
                        @php
                            $metodoPago   = strtolower($mov->pedido?->pago?->pag_metodo_pago ?? '');
                            $esTarjeta    = $mov->mwl_tipo === 'venta' && $metodoPago === 'tarjeta';
                            $esEfectivo   = $mov->mwl_tipo === 'venta' && $metodoPago !== 'tarjeta';
                            $esLiquidado  = $mov->mwl_tipo === 'liquidacion';
                            $esDescuento  = $mov->mwl_tipo === 'comision';
                            $esAjuste     = $mov->mwl_tipo === 'ajuste';
                        @endphp
                        <div class="mov-row">
                            {{-- Icono según tipo --}}
                            <div class="mov-icon
                                {{ $esDescuento ? 'mov-icon-otro' : '' }}
                                {{ $esTarjeta   ? 'mov-icon-tarjeta' : '' }}
                                {{ ($esEfectivo || $esLiquidado || $esAjuste) ? 'mov-icon-abono' : '' }}">
                                @if ($esDescuento)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14" /></svg>
                                @elseif ($esTarjeta)
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                                @endif
                            </div>

                            <div class="info-content">
                                <p class="mov-desc">{{ $mov->mwl_descripcion ?? ucfirst($mov->mwl_tipo) }}</p>
                                <p class="mov-fecha">{{ $mov->mwl_fecha->format('d/m/Y H:i') }}</p>
                            </div>

                            {{-- Monto con lógica correcta --}}
                            @if ($esTarjeta)
                                <span class="mov-monto-pendiente">⏳ ${{ number_format($mov->mwl_monto, 2) }}</span>
                            @elseif ($esDescuento)
                                <span class="mov-monto-neg">−${{ number_format($mov->mwl_monto, 2) }}</span>
                            @else
                                <span class="mov-monto-pos">+${{ number_format($mov->mwl_monto, 2) }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="info-card">
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#d4edaa">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75" />
                        </svg>
                        <p>Completa tu primera entrega para ver tus ganancias aquí.</p>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- BOTTOM NAV --}}
    <nav class="bottom-nav">
        <a href="{{ route('repartidor.index') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25" />
            </svg>
        </a>
        <a href="{{ route('repartidor.historial') }}" class="nav-item">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15" />
            </svg>
        </a>
        <a href="{{ route('repartidor.perfil') }}" class="nav-item active">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </a>
    </nav>

</div>
</body>
</html>
